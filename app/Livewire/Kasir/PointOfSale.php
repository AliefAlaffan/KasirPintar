<?php

namespace App\Livewire\Kasir;

use App\Models\Category;
use App\Models\Product;
use App\Services\TransactionService;
use Livewire\Component;

class PointOfSale extends Component
{
    public string $search = '';
    public ?int $categoryFilter = null;
    public array $cart = [];

    public float $discount = 0;
    public string $paymentMethod = 'cash';
    public float $paidAmount = 0;

    public ?string $errorMessage = null;
    public ?string $successMessage = null;
    public ?int $lastTransactionId = null;

    protected TransactionService $transactionService;

    public function boot(TransactionService $transactionService)
    {
        $this->transactionService = $transactionService;
    }

    public function getProductsProperty()
    {
        return Product::query()
            ->where('is_active', true)
            ->where('stock', '>', 0)
            ->when($this->search, fn ($q) => $q->where(function ($q2) {
                $q2->where('name', 'like', "%{$this->search}%")
                   ->orWhere('sku', 'like', "%{$this->search}%");
            }))
            ->when($this->categoryFilter, fn ($q) => $q->where('category_id', $this->categoryFilter))
            ->orderBy('name')
            ->limit(24)
            ->get();
    }

    public function getCategoriesProperty()
    {
        return Category::orderBy('name')->get();
    }

    // Dipanggil saat kasir scan barcode / ketik SKU lalu tekan Enter
    public function scanBarcode(): void
    {
        $this->errorMessage = null;
        $product = Product::where('sku', $this->search)->where('is_active', true)->first();

        if (!$product) {
            $this->errorMessage = "Produk dengan kode '{$this->search}' tidak ditemukan.";
            return;
        }

        $this->addToCart($product->id);
        $this->search = '';
    }

    public function addToCart(int $productId): void
    {
        $this->errorMessage = null;
        $product = Product::find($productId);

        if (!$product || $product->stock < 1) {
            $this->errorMessage = 'Stok produk ini habis.';
            return;
        }

        if (isset($this->cart[$productId])) {
            if ($this->cart[$productId]['quantity'] + 1 > $product->stock) {
                $this->errorMessage = "Stok {$product->name} hanya tersisa {$product->stock}.";
                return;
            }
            $this->cart[$productId]['quantity']++;
        } else {
            $this->cart[$productId] = [
                'id'       => $product->id,
                'name'     => $product->name,
                'sku'      => $product->sku,
                'price'    => (float) $product->sell_price,
                'quantity' => 1,
                'maxStock' => $product->stock,
                'unit'     => $product->unit,
            ];
        }
    }

    public function incrementQty(int $productId): void
    {
        $product = Product::find($productId);
        if ($this->cart[$productId]['quantity'] + 1 > $product->stock) {
            $this->errorMessage = "Stok {$product->name} hanya tersisa {$product->stock}.";
            return;
        }
        $this->cart[$productId]['quantity']++;
        $this->errorMessage = null;
    }

    public function decrementQty(int $productId): void
    {
        if ($this->cart[$productId]['quantity'] > 1) {
            $this->cart[$productId]['quantity']--;
        } else {
            unset($this->cart[$productId]);
        }
    }

    public function removeFromCart(int $productId): void
    {
        unset($this->cart[$productId]);
    }

    public function getSubtotalProperty(): float
    {
        return collect($this->cart)->sum(fn ($item) => $item['price'] * $item['quantity']);
    }

    public function getTotalProperty(): float
    {
        return max(0, $this->subtotal - $this->discount);
    }

    public function getChangeProperty(): float
    {
        return max(0, $this->paidAmount - $this->total);
    }

    public function checkout(): void
    {
        $this->errorMessage = null;

        if (empty($this->cart)) {
            $this->errorMessage = 'Keranjang masih kosong.';
            return;
        }

        if ($this->paidAmount < $this->total) {
            $this->errorMessage = 'Jumlah bayar kurang dari total belanja.';
            return;
        }

        try {
            $transaction = $this->transactionService->checkout(
                $this->cart,
                $this->discount,
                $this->paymentMethod,
                $this->paidAmount
            );

            $this->lastTransactionId = $transaction->id;
            $this->successMessage = "Transaksi {$transaction->invoice_number} berhasil!";

            // Reset keranjang untuk transaksi berikutnya
            $this->cart = [];
            $this->discount = 0;
            $this->paidAmount = 0;

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function render()
    {
        return view('livewire.kasir.point-of-sale', [
            'products'   => $this->products,
            'categories' => $this->categories,
        ]);
    }
}   
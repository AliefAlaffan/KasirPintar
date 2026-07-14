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

    public $discount = 0;
    public string $paymentMethod = 'cash';
    public $paidAmount = 0;

    public ?string $errorMessage = null;
    public ?string $successMessage = null;
    public ?int $lastTransactionId = null;
    public array $lastReceipt = [];

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
        $discount = is_numeric($this->discount) ? (float) $this->discount : 0;
        return max(0, $this->subtotal - $discount);
    }

    public function getChangeProperty(): float
    {
        $paidAmount = is_numeric($this->paidAmount) ? (float) $this->paidAmount : 0;
        return max(0, $paidAmount - $this->total);
    }

    public function getQuickAmountsProperty(): array
    {
        $total = $this->total;
        $denominations = [5000, 10000, 20000, 50000, 100000, 150000, 200000, 500000];

        return collect($denominations)
            ->filter(fn ($d) => $d > $total)
            ->take(3)
            ->values()
            ->toArray();
    }

    public function setQuickAmount(int $amount): void
    {
        $this->paidAmount = $amount;
    }

    public function setExactAmount(): void
    {
        $this->paidAmount = $this->total;
    }

    // Dipanggil otomatis oleh Livewire setiap kali $paymentMethod berubah
    public function updatedPaymentMethod(string $value): void
    {
        // QRIS & Debit selalu dibayar pas (tidak ada uang kembalian secara fisik)
        if (in_array($value, ['qris', 'debit'])) {
            $this->paidAmount = $this->total;
        } else {
            $this->paidAmount = 0;
        }
    }

    public function checkout(): void
    {
        $this->errorMessage = null;

        if (empty($this->cart)) {
            $this->errorMessage = 'Keranjang masih kosong.';
            return;
        }

        $discount = is_numeric($this->discount) ? (float) $this->discount : 0;
        $paidAmount = is_numeric($this->paidAmount) ? (float) $this->paidAmount : 0;

        if ($paidAmount < $this->total) {
            $this->errorMessage = 'Jumlah bayar kurang dari total belanja.';
            return;
        }

        try {
            $transaction = $this->transactionService->checkout(
                $this->cart,
                $discount,
                $this->paymentMethod,
                $paidAmount
            );

            // Simpan snapshot transaksi untuk ditampilkan di modal, SEBELUM cart direset
            $this->lastReceipt = [
                'invoice_number' => $transaction->invoice_number,
                'items'          => array_values($this->cart),
                'subtotal'       => $this->subtotal,
                'discount'       => $discount,
                'total'          => $this->total,
                'paid_amount'    => $paidAmount,
                'change_amount'  => max(0, $paidAmount - $this->total),
                'payment_method' => $this->paymentMethod,
                'created_at'     => now()->format('d M Y, H:i'),
            ];

            $this->lastTransactionId = $transaction->id;
            $this->successMessage = "Transaksi {$transaction->invoice_number} berhasil!";

            $this->cart = [];
            $this->discount = 0;
            $this->paidAmount = 0;

        } catch (\Exception $e) {
            $this->errorMessage = $e->getMessage();
        }
    }

    public function closeReceipt(): void
    {
        $this->lastTransactionId = null;
        $this->successMessage = null;
        $this->lastReceipt = [];
    }

    public function render()
    {
        return view('livewire.kasir.point-of-sale', [
            'products'   => $this->products,
            'categories' => $this->categories,
        ]);
    }
}
@extends('layouts.home.master')

@section('title')
    Bakebuns
@endsection


@section('content')
    <div class="row">
        <div class="col-md-8">
            <form action="">
                <input type="email" class="form-control" data-search placeholder="search">
            </form>

            <div class="row" id="list-product">
                @foreach ($data as $item)
                    <div class="col-md-4" data-filter-item data-filter-name="{{ $item->title }}">
                        <a href="#" class="text-dark"
                            onclick="event.preventDefault();
                        document.getElementById('barang-{{ $item->id }}').submit();">
                            <div class="card p-3">
                                <img src="{{ asset('img/' . $item->image) }}" class="img-product" alt="">
                                <h2 class="text-center">{{ $item->title }}</h2>
                                <hr class="mt-1 mb-3">
                                <div class="d-flex justify-content-between">
                                    <p class="mb-0">Rp {{ number_format($item->harga) }}</p>
                                    <p class="mb-0"><i class="fas fa-plus me-1"></i>Add</p>
                                </div>
                            </div>
                        </a>

                        <form id="barang-{{ $item->id }}" action="{{ route('addtocart') }}" method="POST"
                            class="d-none">
                            @csrf
                            <input type="text" hidden name="id" value="{{ $item->id }}">
                        </form>
                    </div>
                @endforeach
            </div>
        </div>
        <div class="col-md-4">
            <div class="d-flex justify-content-between border-bottom border-dark">
                <p>Current Order</p>
                <a href="#" class="text-danger"
                    onclick="event.preventDefault();
                document.getElementById('clear').submit();">Clear
                    All</a>

                <form id="clear" action="{{ route('addtocart') }}" method="POST" class="d-none">
                    @csrf @method('DELETE')
                </form>
            </div>

            <div class="cart">
                {{-- kalo ada data di perulangan maka tampilkan data --}}
                @forelse ($cart as $keranjang)
                    <div class="list-cart row">
                        <div class="col-9">
                            <div class="d-flex">
                                <img src="{{ asset('img/' . $keranjang->product->image) }}" class="img-cart" alt="">
                                <div class="d-flex justify-content-start w-100">
                                    <div class="detail-cart">
                                        <p>{{ $keranjang->product->title }}</p>
                                        <small class="text-success">Rp {{ number_format($keranjang->product->harga) }}
                                        </small><strong>x{{ $keranjang->jumlah }}</strong>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-2">
                            <button
                                onclick="event.preventDefault();
                    document.getElementById('hapus-{{ $keranjang->id }}').submit();"
                                class="btn btn-danger btn-sm me-4"><i class="fas fa-trash"></i> Hapus</button>
                            <form id="hapus-{{ $keranjang->id }}" action="{{ route('hapus_item', $keranjang->id) }}"
                                method="POST" class="d-none">
                                @csrf @method('DELETE')
                            </form>
                        </div>
                    </div>

                    {{-- kalo kosong tampilkan gambar kosong --}}
                @empty
                    <img src="https://cdn3.iconfinder.com/data/icons/shopping-and-ecommerce-29/90/not_available-512.png"
                        style="width: 70%; height:100% margin: 0 auto;">
                @endforelse
            </div>
            <hr>
            <div class="promo-zYa">
                <p class="title-coupon">Select available promo to apply</p>
            </div>

            <!-- total and subtotal -->
            <div class="count">
                <div class="d-flex justify-content-between">
                    <p class="mb-2 fw-bold">Subtotal</p>
                    <p class="mb-2">Rp {{ number_format($cart->sum('harga')) }}</p>
                </div>
                @if (session('potongan'))
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fw-bold">Diskon</p>
                        <p class="mb-0">{{ session('potongan') }}%</p>
                    </div>
                @else
                    <div class="d-flex justify-content-between">
                        <p class="mb-0 fw-bold">Diskon</p>
                        <p class="mb-0">-</p>
                    </div>
                @endif
                <form class="row g-3 mt-0" action="" method="POST">
                    @csrf @method('PATCH')
                    <div class="col-10">
                        <label for="inputPassword2" class="visually-hidden">Masukan KODE</label>
                        <input type="text" name="kode" class="form-control" id="inputPassword2"
                            placeholder="masukan voucher">
                    </div>
                    <div class="col-2">
                        <button type="submit" class="btn btn-primary mb-3"
                            style="height: 50px; border-radius: 15px;box-shadow: 0px 10px 15px -3px rgb(0 0 0 / 10%);">CEK</button>
                    </div>
                </form>
                <hr>
                @if (session('potongan'))
                    @php
                        $potongan = ($cart->sum('harga') * session('potongan')) / 100;
                        $total = $cart->sum('harga') - $potongan;
                    @endphp
                    <div class="d-flex justify-content-between">
                        <h2 class="fw-bold">Total</h2>
                        <h2 class="fw-bold">Rp {{ number_format($total) }}</h2>
                    </div>
                @else
                    <div class="d-flex justify-content-between">
                        <h2 class="fw-bold">Total</h2>
                        <h2 class="fw-bold">Rp {{ number_format($cart->sum('harga')) }}</h2>
                    </div>
                @endif
                <button class="btn btn-primary w-100 mt-3" data-bs-toggle="modal" data-bs-target="#exampleModal">Pay
                    Now</button>
            </div>
        </div>
    </div>

    <!-- Modal -->
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h1 class="modal-title fs-5" id="exampleModalLabel">Preview Orderan</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="d-flex justify-content-between">
                        @if (session('potongan'))
                            @php
                                $potongan = ($cart->sum('harga') * session('potongan')) / 100;
                                $total = $cart->sum('harga') - $potongan;
                            @endphp

                            <h2 class="fw-bold">TOTAL</h2>
                            <h2 class="fw-bold harga">Rp {{ number_format($total) }}</h2>
                            <input type="text" value="{{ $total }}" hidden id="subharga">
                        @else
                            <h2 class="fw-bold">TOTAL</h2>
                            <h2 class="fw-bold harga">Rp {{ number_format($cart->sum('harga')) }}</h2>
                            <input type="text" value="{{ $cart->sum('harga') }}" hidden id="subharga">
                        @endif

                    </div>
                    <hr>
                    <div class="mb-3">
                        <label for="exampleInputPassword1" class="form-label">Masukan Jumlah Uang</label>
                        <input type="number" class="form-control" id="uang">
                    </div>
                    <hr>
                    <div class="d-flex justify-content-between">
                        <h5 class="fw-bold">Kembalian</h5>
                        <h5 class="fw-bold kembalian">Rp {{ number_format($cart->sum('harga')) }}</h5>
                    </div>
                    <hr>

                    <div class="ticket w-100">
                        <p class="centered">Bukti Transaksi
                            <br>Purwokerto Selatan
                            <br>Kab Banyumas Jawa Tengah
                        </p>
                        <table class="w-100">
                            <thead>
                                <tr>
                                    <th class="quantity">Q.</th>
                                    <th class="description">Description</th>
                                    <th class="price">Harga</th>
                                </tr>
                            </thead>
                            <tbody>

                                @forelse ($cart as $item_ku)
                                    <tr>
                                        <td class="quantity">{{ $item_ku->jumlah }}</td>
                                        <td class="description">{{ $item_ku->product->title }}</td>
                                        <td class="price">Rp {{ number_format($item_ku->product->harga) }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="3" class="text-center">Item Kosong</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                        <p class="centered">Thanks for your purchase!
                            <br>Happpy Shopping
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('styles')
    <style>
        td,
        th,
        tr,
        table {
            border-top: 1px solid black;
            border-collapse: collapse;
            width: 100%
        }

        td.description,
        th.description {
            width: 75px;
            max-width: 75px;
        }

        td.quantity,
        th.quantity {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        td.price,
        th.price {
            width: 40px;
            max-width: 40px;
            word-break: break-all;
        }

        .centered {
            text-align: center;
            align-content: center;
            margin-top: 40px
        }

        .ticket {
            width: 100%
        }

        @media print {

            .hidden-print,
            .hidden-print * {
                display: none !important;
            }
        }
    </style>
@endsection

@section('scripts')
    <script type="text/javascript">
        $(document).ready(function() {
            // persiapkan document html
            $('#uang').keyup(function() {
                // membaca setiap inputan uang
                var sub_total = $('#subharga').val();
                // variabel sub_harga dari total pembelajaan
                var uang = this.value;
                // jumlah uang
                var kembalian = sub_total - uang;
                // kembalian akan di kurang dari total harga dengan jumlah uang
                $('.kembalian').html(new Intl.NumberFormat('id-ID', {
                    style: 'currency',
                    currency: 'IDR',
                    maximumSignificantDigits: 3
                }).format(kembalian))
                // ubah ke format mata uang idr 
            });
        });
    </script>
@endsection

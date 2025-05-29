@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <!-- row -->
    <div class="row">
        <!-- column -->
        <div class="col-xl-12">
            <div class="card">
                <div class="card-body pb-2">
                    <h1 class="text-center no-border font-w600 fs-60 mt-2"><span class="text-success">Buy</span> and <span
                            class="text-danger">Sell</span> Coins at the<br> Jiade with no additional charges</h1>
                    <h4 class="text-center ">Trusted by millions user with over $1 Trillion in crypto transactions.</h4>
                    <div class="row">
                        <div class="col-xl-12">
                            <div class="text-center mt-3 row justify-content-center">
                                <div class="col-xl-5">
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-6">
                                            <input type="number" class="form-control mb-3" name="value"
                                                placeholder="" value="18.1548">
                                        </div>
                                        <div class="col-xl-6 col-sm-6">
                                            <select class="default-select exchange-select form-control" name="state">
                                                <option value="BTC">BTC</option>
                                                <option value="BTC">Ethereum</option>
                                                <option value="Ripple">Ripple</option>
                                                <option value="Ripple">Bitcoin Cash</option>
                                                <option value="Ripple">Cardano</option>
                                                <option value="Ripple">Litecoin</option>
                                                <option value="Ripple">NEO</option>
                                                <option value="Ripple">Stellar</option>
                                                <option value="Ripple">EOS</option>
                                                <option value="Ripple">NEM</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                                <div class=" col-xl-1">
                                    <div class="equalto">
                                        =
                                    </div>
                                </div>
                                <div class="col-xl-5">
                                    <div class="row">
                                        <div class="col-xl-6 col-sm-6">
                                            <input type="number" class="form-control mb-3" name="value"
                                                placeholder="" value="264.158">
                                        </div>
                                        <div class="col-xl-6 col-sm-6">
                                            <select class="default-select exchange-select form-control" name="state">
                                                <option value="INR">INR</option>
                                                <option value="USD">POUND</option>
                                                <option value="USD">USD</option>
                                                <option value="EURO">EURO</option>
                                            </select>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="text-center mt-4 mb-4">
                                <a href="{{ url('p2p') }}" class="btn btn-warning mx-auto btn-sm">EXCHANGE NOW</a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <!-- row -->
            <div class="row">
                <!-- column -->
                <div class="col-lg-6 col-xl-3 col-sm-6">
                    <div class="card overflow-hidden">
                        <div class="card-body py-0 pt-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Bitcoin Sold</h4>
                                <div class="d-flex align-items-center">
                                    <h2 class="count-num">123k</h2>
                                    <span class="fs-16 font-w500 text-success ps-2"><i
                                            class="bi bi-caret-up-fill pe-2"></i></span>
                                </div>
                            </div>
                            <div id="totalInvoices"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-sm-6">
                    <div class="card overflow-hidden">
                        <div class="card-body py-0 pt-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Amount Refund</h4>
                                <div class="d-flex align-items-center">
                                    <h2 class="count-num">82k</h2>
                                    <span class="fs-16 font-w500 text-danger ps-2"><i
                                            class="bi bi-caret-down-fill pe-2"></i></span>
                                </div>
                            </div>
                            <div id="paidinvoices"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-sm-6">
                    <div class="card overflow-hidden">
                        <div class="card-body py-0 pt-4">
                            <div class="d-flex align-items-center justify-content-between">
                                <h4 class="mb-0">Litecoin Sold</h4>
                                <div class="d-flex align-items-center">
                                    <h2 class="count-num">259k</h2>
                                    <span class="fs-16 font-w500 text-success ps-2"><i
                                            class="bi bi-caret-up-fill pe-2"></i></span>
                                </div>
                            </div>
                            <div id="barChart"></div>
                        </div>
                    </div>
                </div>
                <div class="col-lg-6 col-xl-3 col-sm-6">
                    <div class="card overflow-hidden">
                        <div class="card-body py-0">
                            <div class="d-flex align-items-center justify-content-between">
                                <div class="me-3">
                                    <h2 class=" count-num mb-0">3468</h2>
                                    <p class="mb-0">Dash Sold</p>
                                </div>
                                <div id="ticketSold"></div>
                            </div>
                            <div class="progress mb-2" style="height:10px;">
                                <div class="progress-bar bg-warning progress-animated" style="width: 30%; height:10px;"
                                    role="progressbar">
                                </div>
                            </div>
                            <p>30% than last month</p>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-xl-12">
            <!-- row -->
            <div class="row">
                <!-- column-->
                <div class="col-xl-8 col-lg-12">
                    <div class="card">
                        <div class="card-header justify-content-between border-0">
                            <h2 class="card-title mb-0">Latest Sold Transaction</h2>
                        </div>
                        <div class="card-body px-3 py-0">
                            <div class="table-responsive">
                                <table
                                    class="table-responsive table shadow-hover tickettable display mb-4 dataTablesCard dataTable no-footer"
                                    id="example6">
                                    <thead>
                                        <tr>
                                            <th class="border-bottom">
                                                <input type="checkbox" class="form-check-input" id="checkAll"
                                                    required="">
                                            </th>
                                            <th class="border-bottom ps-0">Currency</th>
                                            <th class="border-bottom">Date</th>
                                            <th class="border-bottom">Email</th>
                                            <th class="border-bottom">Price</th>
                                            <th class="text-end">Status</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>
                                                <div class="checkbox me-0 align-self-center">
                                                    <div class="custom-control custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check8" required="">
                                                        <label class="custom-control-label" for="check8"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <span class="font-w600 fs-14"> #TCK-01-12344 </span>
                                                <h5 class="mb-0">BTC</h5>
                                            </td>
                                            <td class="fs-14 font-w400">Jan 12, 2022</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ url('email-inbox') }}">
                                                        <div class="icon-box icon-box-sm bg-primary">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0"><a href="{{ url('app-profile') }}">Samanta
                                                                William</a></h5>
                                                        <span class="fs-14 text-muted">samantha@mail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$75,00
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-sm badge-success">Paid</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox me-0 align-self-center">
                                                    <div class="custom-control custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check81" required="">
                                                        <label class="custom-control-label" for="check8"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <span class="font-w600 fs-14"> #TCK-01-12344 </span>
                                                <h5 class="mb-0">BCTD</h5>
                                            </td>
                                            <td class="fs-14 font-w400">Jan 12, 2022</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ url('email-inbox') }}">
                                                        <div class="icon-box icon-box-sm bg-primary">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0"><a href="{{ url('app-profile') }}">Tony Soap</a>
                                                        </h5>
                                                        <span class="fs-14 text-muted">demo@mail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$80,00
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-sm badge-success">Paid</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox me-0 align-self-center">
                                                    <div class="custom-control custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check813" required="">
                                                        <label class="custom-control-label" for="check8"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <span class="font-w600 fs-14"> #TCK-01-12344 </span>
                                                <h5 class="mb-0">ETH</h5>
                                            </td>
                                            <td class="fs-14 font-w400">Jan 12, 2022</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ url('email-inbox') }}">
                                                        <div class="icon-box icon-box-sm bg-primary">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nela Vita</a>
                                                        </h5>
                                                        <span class="fs-14 text-muted">demo@mail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$80,00
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-sm badge-warning">Pending</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox me-0 align-self-center">
                                                    <div class="custom-control custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check814" required="">
                                                        <label class="custom-control-label" for="check8"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <span class="font-w600 fs-14"> #TCK-01-12344 </span>
                                                <h5 class="mb-0">USD</h5>
                                            </td>
                                            <td class="fs-14 font-w400">Jan 12, 2022</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ url('email-inbox') }}">
                                                        <div class="icon-box icon-box-sm bg-primary">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nadia Edja</a>
                                                        </h5>
                                                        <span class="fs-14 text-muted">demo@mail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$75,00
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-sm badge-danger">Unpaid</span>
                                            </td>
                                        </tr>
                                        <tr>
                                            <td>
                                                <div class="checkbox me-0 align-self-center">
                                                    <div class="custom-control custom-checkbox ">
                                                        <input type="checkbox" class="form-check-input"
                                                            id="check815" required="">
                                                        <label class="custom-control-label" for="check8"></label>
                                                    </div>
                                                </div>
                                            </td>
                                            <td class="ps-0">
                                                <span class="font-w600 fs-14"> #TCK-01-12344 </span>
                                                <h5 class="mb-0">USD</h5>
                                            </td>
                                            <td class="fs-14 font-w400">Jan 12, 2022</td>
                                            <td>
                                                <div class="d-flex align-items-center">
                                                    <a href="{{ url('email-inbox') }}">
                                                        <div class="icon-box icon-box-sm bg-primary">
                                                            <svg width="24" height="24" viewBox="0 0 24 24"
                                                                fill="none" xmlns="http://www.w3.org/2000/svg">
                                                                <path
                                                                    d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                                    fill="white" />
                                                            </svg>
                                                        </div>
                                                    </a>
                                                    <div class="ms-3">
                                                        <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nadia Edja</a>
                                                        </h5>
                                                        <span class="fs-14 text-muted">demo@mail.com</span>
                                                    </div>
                                                </div>
                                            </td>
                                            <td>$75,00
                                            </td>
                                            <td class="text-end">
                                                <span class="badge badge-sm badge-danger">Unpaid</span>
                                            </td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>

                        </div>
                    </div>
                </div>
                <div class="col-xl-4">
                    <div class="card">
                        <div class="card-header border-0 pb-0">
                            <h4 class="card-title mb-0">Buy Coin</h4>
                        </div>
                        <div class="card-body pt-2">
                            <div class="d-flex align-items-center justify-content-between mt-3 mb-2">
                                <span class="small text-muted">Avbl Balance</span>
                                <span class="text-dark">210.800 USDT</span>
                            </div>
                            <form>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Price</span>
                                    <input type="text" class="form-control">
                                    <span class="input-group-text">USDT</span>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Size</span>
                                    <input type="text" class="form-control">
                                    <button class="btn btn-primary btn-outline-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">USDT
                                    </button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#!">USDT</a></li>
                                        <li><a class="dropdown-item" href="#!">BTC</a></li>
                                    </ul>
                                </div>
                                <div class="mb-3 mt-4">
                                    <label class="form-label">TP/SL</label>
                                    <div class="input-group mb-3">
                                        <input type="text" class="form-control" placeholder="Take Profit">
                                        <button class="btn btn-primary btn-primary btn-outline-primary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">Mark</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#!">Last</a></li>
                                            <li><a class="dropdown-item" href="#!">Mark</a></li>
                                        </ul>
                                    </div>
                                    <div class="input-group mb-3"><input type="text" class="form-control"
                                            placeholder="Stop Loss">
                                        <button class="btn btn-primary btn-outline-primary dropdown-toggle"
                                            type="button" data-bs-toggle="dropdown"
                                            aria-expanded="false">Mark</button>
                                        <ul class="dropdown-menu dropdown-menu-end">
                                            <li><a class="dropdown-item" href="#!">Last</a></li>
                                            <li><a class="dropdown-item" href="#!">Mark</a></li>
                                        </ul>
                                    </div>
                                </div>
                                <div class="input-group mb-3">
                                    <span class="input-group-text">Stop Price</span>
                                    <input type="text" class="form-control">
                                    <button class="btn btn-primary btn-outline-primary dropdown-toggle" type="button"
                                        data-bs-toggle="dropdown" aria-expanded="false">Mark</button>
                                    <ul class="dropdown-menu dropdown-menu-end">
                                        <li><a class="dropdown-item" href="#!">Limit</a></li>
                                        <li><a class="dropdown-item" href="#!">Mark</a></li>
                                    </ul>
                                </div>
                                <div class="d-flex justify-content-between flex-wrap">
                                    <div class="d-flex">
                                        <div class="">Cost</div>
                                        <div class="text-muted px-1"> 0.00 USDT</div>
                                    </div>
                                    <div class="d-flex">
                                        <div class="">Max</div>
                                        <div class="text-muted px-1"> 6.00 USDT </div>
                                    </div>
                                </div>
                                <div class="mt-4 d-flex justify-content-between">
                                    <a href="javascript:void(0)"
                                        class="btn btn-success btn-sm light text-uppercase btn-block me-3">BUY</a>
                                    <a href="javascript:void(0)"
                                        class="btn btn-danger btn-sm light text-uppercase btn-block ">Sell</a>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
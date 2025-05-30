@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xl-8">
            <div class="card">
                <div class="card-body">
                    <div id="tradingview_85dc0" class=""></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title mb-0">Future Trade</h4>
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
                                <li><a class="dropdown-item" href="#">USDT</a></li>
                                <li><a class="dropdown-item" href="#">BTC</a></li>
                            </ul>
                        </div>
                        <div class="mb-3 mt-4">
                            <label class="form-label">TP/SL</label>
                            <div class="input-group mb-3">
                                <input type="text" class="form-control" placeholder="Take Profit">
                                <button class="btn btn-primary btn-primary btn-outline-primary dropdown-toggle"
                                    type="button" data-bs-toggle="dropdown" aria-expanded="false">Mark</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Last</a></li>
                                    <li><a class="dropdown-item" href="#">Mark</a></li>
                                </ul>
                            </div>
                            <div class="input-group mb-3"><input type="text" class="form-control"
                                    placeholder="Stop Loss">
                                <button class="btn btn-primary btn-outline-primary dropdown-toggle" type="button"
                                    data-bs-toggle="dropdown" aria-expanded="false">Mark</button>
                                <ul class="dropdown-menu dropdown-menu-end">
                                    <li><a class="dropdown-item" href="#">Last</a></li>
                                    <li><a class="dropdown-item" href="#">Mark</a></li>
                                </ul>
                            </div>
                        </div>
                        <div class="input-group mb-3">
                            <span class="input-group-text">Stop Price</span>
                            <input type="text" class="form-control">
                            <button class="btn btn-primary btn-outline-primary dropdown-toggle" type="button"
                                data-bs-toggle="dropdown" aria-expanded="false">Mark</button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                <li><a class="dropdown-item" href="#">Limit</a></li>
                                <li><a class="dropdown-item" href="#">Mark</a></li>
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
                        <div class="mt-3 d-flex justify-content-between">
                            <a href="javascript:void(0)"
                                class="btn btn-success btn-sm light text-uppercase me-3 btn-block">BUY</a>
                            <a href="javascript:void(0)"
                                class="btn btn-danger btn-sm light text-uppercase btn-block">Sell</a>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-xl-4">
            <div class="card">
                <div class="card-header border-0 pb-0">
                    <h4 class="card-title mb-2">Order Book</h4>
                </div>
                <div class="card-body pt-2 dlab-scroll height400">
                    <table class="table shadow-hover orderbookTable">
                        <thead>
                            <tr>
                                <th>Price(USDT)</th>
                                <th>Size(USDT)</th>
                                <th>Total</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>
                                    <span class="text-success">19972.43</span>
                                </td>
                                <td>0.0488</td>
                                <td>6.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-danger">20972.43</span>
                                </td>
                                <td>0.0588</td>
                                <td>5.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-success">19972.43</span>
                                </td>
                                <td>0.0488</td>
                                <td>6.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-success">19850.20</span>
                                </td>
                                <td>0.0388</td>
                                <td>7.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-danger">20972.43</span>
                                </td>
                                <td>0.0588</td>
                                <td>5.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-danger">20972.43</span>
                                </td>
                                <td>0.0588</td>
                                <td>5.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-success">19972.43</span>
                                </td>
                                <td>0.0488</td>
                                <td>6.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-success">19850.20</span>
                                </td>
                                <td>0.0388</td>
                                <td>7.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-danger">20972.43</span>
                                </td>
                                <td>0.0588</td>
                                <td>5.8312</td>
                            </tr>
                            <tr>
                                <td>
                                    <span class="text-danger">20972.43</span>
                                </td>
                                <td>0.0588</td>
                                <td>5.8312</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <div class="col-xl-8">
            <div class="card">
                <div class="card-header border-0 pb-3 flex-wrap">
                    <h4 class="card-title">Trade Status</h4>
                    <nav>
                        <div class="nav nav-pills light" id="nav-tab" role="tablist">
                            <button class="nav-link active" id="nav-order-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-order" type="button" role="tab"
                                aria-selected="true">Order</button>
                            <button class="nav-link" id="nav-histroy-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-history" type="button" role="tab"
                                aria-selected="false">Order History</button>
                            <button class="nav-link" id="nav-trade-tab" data-bs-toggle="tab"
                                data-bs-target="#nav-trade" type="button" role="tab"
                                aria-selected="false">Trade Histroy</button>
                        </div>
                    </nav>
                </div>
                <div class="card-body pt-0">
                    <div class="tab-content" id="nav-tabContent">
                        <div class="tab-pane fade show active" id="nav-order" role="tabpanel"
                            aria-labelledby="nav-order-tab">
                            <div class="table-responsive dataTabletrade">
                                <table id="example-2" class="table display orderbookTable" style="min-width:845px">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Trade</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td class="text-end">$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td class="text-end">$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td class="text-end">$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td class="text-end">$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td class="text-end">$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td class="text-end">$372,000</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-history" role="tabpanel">
                            <div class="table-responsive dataTabletrade">
                                <table id="example-history-1" class="table display" style="min-width:845px">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Trade</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td class="text-end">$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td class="text-end">$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td class="text-end">$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td class="text-end">$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td class="text-end">$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td class="text-end">$372,000</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                        <div class="tab-pane fade" id="nav-trade" role="tabpanel" aria-labelledby="nav-trade-tab">
                            <div class="table-responsive dataTabletrade">
                                <table id="example-history-2" class="table display" style="min-width:845px">
                                    <thead>
                                        <tr>
                                            <th>Name</th>
                                            <th>Trade</th>
                                            <th>Location</th>
                                            <th>Price</th>
                                            <th>Date</th>
                                            <th class="text-end">Amount</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td class="text-end">$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td class="text-end">$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td class="text-end">$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td class="text-end">$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td class="text-end">$162,700</td>
                                        </tr>
                                        <tr>
                                            <td>Brielle Williamson</td>
                                            <td>Integration Specialist</td>
                                            <td>New York</td>
                                            <td>61</td>
                                            <td>2012/12/02</td>
                                            <td class="text-end">$372,000</td>
                                        </tr>
                                        <tr>
                                            <td>Tiger Nixon</td>
                                            <td>System Architect</td>
                                            <td>Edinburgh</td>
                                            <td>61</td>
                                            <td>2011/04/25</td>
                                            <td class="text-end">$320,800</td>
                                        </tr>
                                        <tr>
                                            <td>Garrett Winters</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>63</td>
                                            <td>2011/07/25</td>
                                            <td class="text-end">$170,750</td>
                                        </tr>
                                        <tr>
                                            <td>Ashton Cox</td>
                                            <td>Junior Technical Author</td>
                                            <td>San Francisco</td>
                                            <td>66</td>
                                            <td>2009/01/12</td>
                                            <td class="text-end">$86,000</td>
                                        </tr>
                                        <tr>
                                            <td>Cedric Kelly</td>
                                            <td>Senior Javascript Developer</td>
                                            <td>Edinburgh</td>
                                            <td>22</td>
                                            <td>2012/03/29</td>
                                            <td class="text-end">$433,060</td>
                                        </tr>
                                        <tr>
                                            <td>Airi Satou</td>
                                            <td>Accountant</td>
                                            <td>Tokyo</td>
                                            <td>33</td>
                                            <td>2008/11/28</td>
                                            <td class="text-end">$162,700</td>
                                        </tr>

                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-xxl-12">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table  tickettable display mb-4 no-footer" id="example6">
                            <thead>
                                <tr>
                                    <th>
                                        <input type="checkbox" class="form-check-input" id="checkAll" required="">
                                    </th>
                                    <th>Event</th>
                                    <th>Date</th>
                                    <th>Email</th>
                                    <th>Price</th>
                                    <th class="text-end">Status</th>
                                </tr>
                            </thead>
                            <tbody>
                                <tr>
                                    <td>
                                        <div class="checkbox me-0 align-self-center">
                                            <div class="custom-control custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="check8"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Samanta William</a></h5>
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
                                                <input type="checkbox" class="form-check-input" id="check81"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Tony Soap</a></h5>
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
                                                <input type="checkbox" class="form-check-input" id="check82"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nela Vita</a></h5>
                                                <span class="fs-14 text-muted">demo@mail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$80,00
                                    </td>
                                    <td class="text-end">
                                        <span class="badge badge-sm badge-danger">Unpaid</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="checkbox me-0 align-self-center">
                                            <div class="custom-control custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="check83"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Karen Hope</a></h5>
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
                                                <input type="checkbox" class="form-check-input" id="check84"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nadia Edja</a></h5>
                                                <span class="fs-14 text-muted">demo@mail.com</span>
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
                                                <input type="checkbox" class="form-check-input" id="check85"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Samanta William</a></h5>
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
                                                <input type="checkbox" class="form-check-input" id="check86"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Tony Soap</a></h5>
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
                                                <input type="checkbox" class="form-check-input" id="check87"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nela Vita</a></h5>
                                                <span class="fs-14 text-muted">demo@mail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$80,00
                                    </td>
                                    <td class="text-end">
                                        <span class="label label-danger">Unpaid</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="checkbox me-0 align-self-center">
                                            <div class="custom-control custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="check88"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Karen Hope</a></h5>
                                                <span class="fs-14 text-muted">demo@mail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$80,00
                                    </td>
                                    <td class="text-end">
                                        <span class="label label-warning">Panding</span>
                                    </td>
                                </tr>
                                <tr>
                                    <td>
                                        <div class="checkbox me-0 align-self-center">
                                            <div class="custom-control custom-checkbox ">
                                                <input type="checkbox" class="form-check-input" id="check89"
                                                    required="">
                                                <label class="custom-control-label" for="check8"></label>
                                            </div>
                                        </div>
                                    </td>
                                    <td>
                                        <span class="font-w600 fs-14"> #ID-01-12344 </span>
                                    </td>
                                    <td class="fs-14 font-w400">Jan 12, 2022</td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <a href="{{ url('email-inbox') }}">
                                                <div class="icon-box icon-box-sm  bg-primary">
                                                    <svg width="24" height="24" viewBox="0 0 24 24"
                                                        fill="none" xmlns="http://www.w3.org/2000/svg">
                                                        <path
                                                            d="M19 4H5C4.20435 4 3.44129 4.31607 2.87868 4.87868C2.31607 5.44129 2 6.20435 2 7V17C2 17.7956 2.31607 18.5587 2.87868 19.1213C3.44129 19.6839 4.20435 20 5 20H19C19.7956 20 20.5587 19.6839 21.1213 19.1213C21.6839 18.5587 22 17.7956 22 17V7C22 6.20435 21.6839 5.44129 21.1213 4.87868C20.5587 4.31607 19.7956 4 19 4ZM18.427 6L12.6 10.8C12.4335 10.9267 12.2312 10.9976 12.022 11.0026C11.8129 11.0077 11.6074 10.9465 11.435 10.828L5.573 6H18.427ZM19 18H5C4.73478 18 4.48043 17.8946 4.29289 17.7071C4.10536 17.5196 4 17.2652 4 17V7.3L10.2 12.4C10.7159 12.7863 11.3435 12.9944 11.988 12.993C12.6551 12.992 13.3037 12.774 13.836 12.372L20 7.3V17C20 17.2652 19.8946 17.5196 19.7071 17.7071C19.5196 17.8946 19.2652 18 19 18Z"
                                                            fill="white" />
                                                    </svg>
                                                </div>
                                            </a>
                                            <div class="ms-3">
                                                <h5 class="mb-0"><a href="{{ url('app-profile') }}">Nadia Edja</a></h5>
                                                <span class="fs-14 text-muted">demo@mail.com</span>
                                            </div>
                                        </div>
                                    </td>
                                    <td>$75,00
                                    </td>
                                    <td class="text-end">
                                        <span class="badge badge-sm badge-success">Paid</span>
                                    </td>
                                </tr>
                            </tbody>
                        </table>
                    </div>

                </div>
            </div>
        </div>
    </div>
</div>
@endsection
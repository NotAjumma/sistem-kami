@extends('layouts.default')
@section('content')
<div class="container-fluid">
    <!--element-area-->
    <div class="element-area">
        <div class="demo-view">
            <div class="container-fluid pt-0 ps-0 pe-lg-4 pe-0">
                <!-- row -->
                <div class="row">
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="card-title-1">
                            <div class="card-header flex-wrap border-0 pb-0 ">
                                <h5 class="card-title">Card title</h5>
                                <ul class="nav nav-pills light" id="myTab" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent">
                                <div class="tab-pane fade show active" id="CardTitle" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="card-body pt-0">
                                        <p class="card-text">He lay on his armour-like back, and if he lifted his head a
                                            little he could see his brown belly, slightly domed and divided by arches
                                            into stiff <br> sections. The bedding was hardly able to cover it and seemed
                                            ready to
                                            slide off any moment.
                                        </p>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="CardTitle-html" role="tabpanel"
                                    aria-labelledby="home-tab">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card" id="card-title-1"&gt;
&lt;div class="card-header border-0 pb-0 "&gt;
&lt;h5 class="card-title"&gt;Card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;He lay on his armour-like back, and if he lifted his head a little he could see his brown belly, slightly domed and divided by arches into stiff &lt;br&gt; sections. The bedding was hardly able to cover it and seemed ready to
slide off any moment.
&lt;/p&gt;
&lt;/div&gt;
&lt;/div&gt;</code></pre>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="card-title-2">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Card title</h5>
                                <ul class="nav nav-pills light" id="myTab-1" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-1" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle2" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-1" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle2-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-1">
                                <div class="tab-pane fade show active" id="CardTitle2" role="tabpanel"
                                    aria-labelledby="home-tab-1">

                                    <div class="card-body pt-0">
                                        <p class="card-text">This is a wider card with supporting text and below as a
                                            natural lead-in to the additional content. This content is a little <br> bit
                                            longer. Some quick example text to build the bulk</p>
                                    </div>
                                    <div class="card-footer d-sm-flex justify-content-between align-items-center">
                                        <div class="card-footer-link mb-4 mb-sm-0">
                                            <p class="card-text d-inline">Last updated 3 mins ago</p>
                                        </div>

                                        <a href="javascript:void(0);" class="btn btn-primary text-white">Go
                                            somewhere</a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="CardTitle2-html" role="tabpanel"
                                    aria-labelledby="home-tab-1">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card" id="card-title-2"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title"&gt;Card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little &lt;br&gt; bit longer. Some quick example text to build the bulk&lt;/p&gt;
&lt;/div&gt;
&lt;div class="card-footer d-sm-flex justify-content-between align-items-center"&gt;
&lt;div class="card-footer-link mb-4 mb-sm-0"&gt;
&lt;p class="card-text d-inline"&gt;Last updated 3 mins ago&lt;/p&gt;
&lt;/div&gt;

&lt;a href="javascript:void(0);" class="btn btn-outline-primary text-white"&gt;Go somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;/div&gt;    
</code></pre>

                                    </div>
                                </div>
                            </div>

                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card text-center" id="card-title-3">
                            <div class="card-header flex-wrap  border-0">
                                <h5 class="card-title">Card Title</h5>
                                <ul class="nav nav-pills light" id="myTab-2" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-2" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle3" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-2" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle3-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-2">
                                <div class="tab-pane fade show active" id="CardTitle3" role="tabpanel"
                                    aria-labelledby="home-tab-2">
                                    <div class="card-body pt-0">
                                        <p class="card-text">This is a wider card with supporting text and below as a
                                            natural lead-in to the additional content. This content</p>
                                        <a href="javascript:void(0);" class="btn btn-primary text-white">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer">
                                        <p class="card-text ">Last updateed 3 min ago</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="CardTitle3-html" role="tabpanel"
                                    aria-labelledby="home-tab-2">
                                    <div class="card-body p-0 code-area text-start">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-center" id="card-title-3"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title"&gt;Card Title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;This is a wider card with supporting text and below as a natural lead-in to the additional content. This content&lt;/p&gt;
&lt;a href="javascript:void(0);" class="btn btn-outline-primary text-white"&gt;Go somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer"&gt;
&lt;p class="card-text "&gt;Last updateed 3 min ago&lt;/p&gt;
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card text-center" id="special-title">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Special title treatment</h5>
                                <ul class="nav nav-pills light" id="myTab-4" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-4" data-bs-toggle="tab"
                                            data-bs-target="#TitleTreatment" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-4" data-bs-toggle="tab"
                                            data-bs-target="#TitleTreatment-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-4">
                                <div class="tab-pane fade show active" id="TitleTreatment" role="tabpanel"
                                    aria-labelledby="home-tab-4">
                                    <div class="card-body pt-0 custom-tab-1">
                                        <ul class="nav nav-tabs card-body-tabs mb-3">
                                            <li class="nav-item"><a class="nav-link active"
                                                    href="javascript:void(0);">Active</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link"
                                                    href="javascript:void(0);">Link</a>
                                            </li>
                                            <li class="nav-item"><a class="nav-link disabled"
                                                    href="javascript:void(0);">Disabled</a>
                                            </li>
                                        </ul>
                                        <p class="card-text">With supporting text below as a natural lead-in to
                                            additional content.</p><a href="javascript:void(0);"
                                            class="btn btn-primary text-white btn-card">Go somewhere</a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="TitleTreatment-html" role="tabpanel"
                                    aria-labelledby="home-tab-4">
                                    <div class="card-body p-0 code-area text-start">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-center" id="special-title"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title"&gt;Special title treatment&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body custom-tab-1"&gt;
&lt;ul class="nav nav-tabs card-body-tabs mb-3"&gt;
&lt;li class="nav-item"&gt;&lt;a class="nav-link active" href="javascript:void(0);"&gt;Active&lt;/a&gt;
&lt;/li&gt;
&lt;li class="nav-item"&gt;&lt;a class="nav-link" href="javascript:void(0);"&gt;Link&lt;/a&gt;
&lt;/li&gt;
&lt;li class="nav-item"&gt;&lt;a class="nav-link disabled" href="javascript:void(0);"&gt;Disabled&lt;/a&gt;
&lt;/li&gt;
&lt;/ul&gt;
&lt;p class="card-text"&gt;With supporting text below as a natural lead-in to additional content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-primary text-white btn-card"&gt;Go somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;/div&gt;    
</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="primary-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Primary card title</h5>
                                <ul class="nav nav-pills light" id="myTab-5" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-5" data-bs-toggle="tab"
                                            data-bs-target="#Primarycard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-5" data-bs-toggle="tab"
                                            data-bs-target="#Primarycard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-5">
                                <div class="tab-pane fade show active" id="Primarycard" role="tabpanel"
                                    aria-labelledby="home-tab-5">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-primary text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Primarycard-html" role="tabpanel"
                                    aria-labelledby="home-tab-5">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white" id="primary-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Primary card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-primary text-white btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;
</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="secondary-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Secondary card title</h5>
                                <ul class="nav nav-pills light" id="myTab-6" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-6" data-bs-toggle="tab"
                                            data-bs-target="#Secondarycard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-6" data-bs-toggle="tab"
                                            data-bs-target="#Secondarycard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-6">
                                <div class="tab-pane fade show active" id="Secondarycard" role="tabpanel"
                                    aria-labelledby="home-tab-6">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-secondary text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Secondarycard-html" role="tabpanel"
                                    aria-labelledby="home-tab-6">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white " id="secondary-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Secondary card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-secondary text-white btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="success-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Success card title</h5>
                                <ul class="nav nav-pills light" id="myTab-7" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-7" data-bs-toggle="tab"
                                            data-bs-target="#Successcard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-7" data-bs-toggle="tab"
                                            data-bs-target="#Successcard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-7">
                                <div class="tab-pane fade show active" id="Successcard" role="tabpanel"
                                    aria-labelledby="home-tab-7">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-success text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Successcard-html" role="tabpanel"
                                    aria-labelledby="home-tab-7">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white" id="success-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Success card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-success text-white light btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="danger-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Danger card title</h5>
                                <ul class="nav nav-pills light" id="myTab-8" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-8" data-bs-toggle="tab"
                                            data-bs-target="#Dangercard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-8" data-bs-toggle="tab"
                                            data-bs-target="#Dangercard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-8">
                                <div class="tab-pane fade show active" id="Dangercard" role="tabpanel"
                                    aria-labelledby="home-tab-8">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class=" btn btn-danger text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Dangercard-html" role="tabpanel"
                                    aria-labelledby="home-tab-8">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white" id="danger-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Danger card title&lt;/h5&gt;    
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class=" btn btn-outline-danger text-white btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt; </code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="warning-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Warning card title</h5>
                                <ul class="nav nav-pills light" id="myTab-9" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-9" data-bs-toggle="tab"
                                            data-bs-target="#Warningcard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-9" data-bs-toggle="tab"
                                            data-bs-target="#Warningcard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-9">
                                <div class="tab-pane fade show active" id="Warningcard" role="tabpanel"
                                    aria-labelledby="home-tab-9">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-warning text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="Warningcard-html" role="tabpanel"
                                    aria-labelledby="home-tab-9">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white" id="warning-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Warning card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-warning text-white btn-card"&gt;Go
   somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="info-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Info card title</h5>
                                <ul class="nav nav-pills light" id="myTab-10" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-10" data-bs-toggle="tab"
                                            data-bs-target="#Infocard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-10" data-bs-toggle="tab"
                                            data-bs-target="#Infocard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-10">
                                <div class="tab-pane fade show active" id="Infocard" role="tabpanel"
                                    aria-labelledby="home-tab-10">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-info text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">
                                        Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="Infocard-html" role="tabpanel"
                                    aria-labelledby="home-tab-10">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card text-white" id="info-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Info card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-info text-white btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;
Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="light-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Light card title</h5>
                                <ul class="nav nav-pills light" id="myTab-11" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-11" data-bs-toggle="tab"
                                            data-bs-target="#Lightcard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-11" data-bs-toggle="tab"
                                            data-bs-target="#Lightcard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-11">
                                <div class="tab-pane fade show active" id="Lightcard" role="tabpanel"
                                    aria-labelledby="home-tab-11">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p><a href="javascript:void(0);"
                                            class="btn btn-light text-black btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Lightcard-html" role="tabpanel"
                                    aria-labelledby="home-tab-11">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card " id="light-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title"&gt;Light card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;&lt;a href="javascript:void(0);" class="btn btn-outline-light text-white btn-card"&gt;Go
somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="dark-card">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Dark card title</h5>
                                <ul class="nav nav-pills light" id="myTab-12" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-12" data-bs-toggle="tab"
                                            data-bs-target="#Darkcard" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-12" data-bs-toggle="tab"
                                            data-bs-target="#Darkcard-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-12">
                                <div class="tab-pane fade show active" id="Darkcard" role="tabpanel"
                                    aria-labelledby="home-tab-12">
                                    <div class="card-body pt-0 mb-0">
                                        <p class="card-text">Some quick example text to build on the card title and
                                            make up the bulk of the card's content.</p>
                                        <a href="javascript:void(0);" class="btn btn-dark text-white btn-card">Go
                                            somewhere</a>
                                    </div>
                                    <div class="card-footer bg-transparent border-0 text-white">Last updateed 3 min ago
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="Darkcard-html" role="tabpanel"
                                    aria-labelledby="home-tab-12">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"> <code class="language-html">&lt;div class="card text-white" id="dark-card"&gt;
&lt;div class="card-header "&gt;
&lt;h5 class="card-title text-white"&gt;Dark card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body mb-0"&gt;
&lt;p class="card-text"&gt;Some quick example text to build on the card title and make up the bulk of the card's content.&lt;/p&gt;
&lt;a href="javascript:void(0);" class="btn btn-outline-dark text-white btn-card"&gt;Go
   somewhere&lt;/a&gt;
&lt;/div&gt;
&lt;div class="card-footer bg-transparent border-0 text-white"&gt;Last updateed 3 min ago
&lt;/div&gt;
&lt;/div&gt; </code></pre>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card overflow-hidden" id="card-title-4">
                            <img class="card-img-top img-fluid" src="./images/card/1.png" alt="Card image cap">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Card title</h5>
                                <ul class="nav nav-pills light" id="myTab-13" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-13" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle4" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-13" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle4-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-13">
                                <div class="tab-pane fade show active" id="CardTitle4" role="tabpanel"
                                    aria-labelledby="home-tab-13">
                                    <div class="card-body pt-0">
                                        <p class="card-text">This is a wider card with supporting text below as a
                                            natural lead-in to additional content. This content is a little bit longer.
                                        </p>
                                        <p class="card-text">Last updated 3 mins ago</p>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="CardTitle4-html" role="tabpanel"
                                    aria-labelledby="home-tab-13">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card" &gt;
&lt;img class="card-img-top img-fluid" src="./images/card/1.png" alt="Card image cap"&gt;
&lt;div class="card-header flex-wrap"&gt;
&lt;h5 class="card-title"&gt;Card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;This is a wider card with supporting text below as a natural lead-in to additional content. This content is a little bit longer.&lt;/p&gt;
&lt;p class="card-text"&gt;Last updated 3 mins ago&lt;/p&gt;
&lt;/div&gt;
&lt;/div&gt;</code></pre>

                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card overflow-hidden" id="card-title-5">
                            <img class="card-img-top img-fluid" src="./images/card/2.png" alt="Card image cap">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Card title</h5>
                                <ul class="nav nav-pills light" id="myTab-14" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-14" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle5" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-14" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle5-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-14">
                                <div class="tab-pane fade show active" id="CardTitle5" role="tabpanel"
                                    aria-labelledby="home-tab-14">
                                    <div class="card-body pt-0">
                                        <p class="card-text">He lay on his armour-like back, and if he lifted his head
                                            a little
                                        </p>
                                    </div>
                                    <div class="card-footer">
                                        <p class="card-text d-inline">Card footer</p>
                                        <a href="javascript:void(0);" class="card-link float-end">Card link</a>
                                    </div>
                                </div>
                                <div class="tab-pane fade" id="CardTitle5-html" role="tabpanel"
                                    aria-labelledby="home-tab-14">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card" &gt;
&lt;img class="card-img-top img-fluid" src="./images/card/2.png" alt="Card image cap"&gt;
&lt;div class="card-header"&gt;
&lt;h5 class="card-title"&gt;Card title&lt;/h5&gt;

&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;He lay on his armour-like back, and if he lifted his head a little
&lt;/p&gt;
&lt;/div&gt;
&lt;div class="card-footer"&gt;
&lt;p class="card-text d-inline"&gt;Card footer&lt;/p&gt;
&lt;a href="javascript:void(0);" class="card-link float-end"&gt;Card link&lt;/a&gt;
&lt;/div&gt;
&lt;/div&gt; </code></pre>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                    <!-- Column  -->
                    <div class="col-xl-12">
                        <div class="card dz-card" id="card-title-6">
                            <div class="card-header flex-wrap border-0">
                                <h5 class="card-title">Card title</h5>
                                <ul class="nav nav-pills light" id="myTab-15" role="tablist">
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link active " id="home-tab-15" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle6" type="button" role="tab"
                                            aria-selected="true">Preview</button>
                                    </li>
                                    <li class="nav-item" role="presentation">
                                        <button class="nav-link " id="profile-tab-15" data-bs-toggle="tab"
                                            data-bs-target="#CardTitle6-html" type="button" role="tab"
                                            aria-selected="false">HTML</button>
                                    </li>
                                </ul>
                            </div>
                            <div class="tab-content" id="myTabContent-15">
                                <div class="tab-pane fade show active" id="CardTitle6" role="tabpanel"
                                    aria-labelledby="home-tab-15">
                                    <div class="card-body pt-0">
                                        <p class="card-text">This is a wider card with supporting text and below as a
                                            natural lead-in to the additional content. This content is a little</p>
                                    </div>
                                    <img class="card-img-bottom img-fluid" src="./images/card/3.png"
                                        alt="Card image cap">
                                    <div class="card-footer">
                                        <p class="card-text d-inline">Card footer</p>
                                        <a href="javascript:void(0);" class="card-link float-end">Card link</a>
                                    </div>
                                </div>
                                <div class="tab-pane fade " id="CardTitle6-html" role="tabpanel"
                                    aria-labelledby="home-tab-15">
                                    <div class="card-body p-0 code-area">
                                        <pre class="m-0"><code class="language-html">&lt;div class="card"&gt;
&lt;div class="card-header"&gt;
&lt;h5 class="card-title"&gt;Card title&lt;/h5&gt;
&lt;/div&gt;
&lt;div class="card-body"&gt;
&lt;p class="card-text"&gt;This is a wider card with supporting text and below as a natural lead-in to the additional content. This content is a little&lt;/p&gt;
&lt;/div&gt;
&lt;img class="card-img-bottom img-fluid" src="./images/card/3.png" alt="Card image cap"&gt;
&lt;div class="card-footer"&gt;
&lt;p class="card-text d-inline"&gt;Card footer&lt;/p&gt;
&lt;a href="javascript:void(0);" class="card-link float-end"&gt;Card link&lt;/a&gt;
&lt;/div&gt;
&lt;/div&gt;</code></pre>
                                    </div>

                                </div>
                            </div>
                        </div>
                    </div>
                    <!-- /Column  -->
                </div>
            </div>
        </div>
        <div class="demo-right ">
            <div class="demo-right-inner dz-scroll " id="right-sidebar">
                <h4 class="title">Card</h4>
                <div class="dz-scroll demo-right-tabs" id="rightSidebarScroll">
                    <ul class="navbar-nav" id="menu-bar">
                        <li><a href="#card-title-1" class="scroll">Card Title</a></li>
                        <li><a href="#card-title-2" class="scroll">Card Title-2</a></li>
                        <li><a href="#card-title-3" class="scroll">Card Title-3</a></li>
                        <li><a href="#special-title" class="scroll">Special Title Treatment</a></li>
                        <li><a href="#primary-card" class="scroll">primary-card-title</a></li>
                        <li><a href="#secondary-card" class="scroll">Secondary Card Title</a></li>
                        <li><a href="#success-card" class="scroll">Success Card Title</a></li>
                        <li><a href="#danger-card" class="scroll">Danger Card Title</a></li>
                        <li><a href="#warning-card" class="scroll">Warning card title</a></li>
                        <li><a href="#info-card" class="scroll">Info card title</a></li>
                        <li><a href="#light-card" class="scroll">Light card title</a></li>
                        <li><a href="#dark-card" class="scroll">Dark card title</a></li>
                        <li><a href="#card-title-4" class="scroll">Card Title-4</a></li>
                        <li><a href="#card-title-5" class="scroll">Card Title-5</a></li>
                        <li><a href="#card-title-6" class="scroll">Card Title-6</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('scripts')
	

<script>
    hljs.highlightAll();
</script>
@endpush
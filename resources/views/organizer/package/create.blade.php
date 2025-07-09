@extends('layouts.admin.default')
@push('styles')
    <style>
    </style>
@endpush
@section('content')
    <div class="container-fluid">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Create new package</h4>
            </div>
            <div class="card-body">
                <div class="basic-form">
                    <form action="{{ route('organizer.packages.submit_create') }}" method="POST">
                        @csrf
                        <div class="row">
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Package Name</label>
                                <input type="text" class="form-control" name="name" placeholder="Package Name" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Slug</label>
                                <input type="text" class="form-control" name="slug" placeholder="slug-name" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Package Code</label>
                                <input type="text" class="form-control" name="package_code" placeholder="PKG">
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Category</label>
                                <select class="form-select" name="category_id" required>
                                    <option value="">Choose category</option>
                                    @foreach ($categories as $category)
                                        <option value="{{ $category->id }}">{{ $category->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Base Price</label>
                                <input type="number" step="0.01" class="form-control" name="base_price" required>
                            </div>
                            <label class="form-label">Package Discount</label>
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <select name="discount[type]" class="form-select">
                                        <option value="percentage">Percentage</option>
                                        <option value="fixed">Fixed</option>
                                    </select>
                                </div>
                                <div class="col-md-2"><input type="number" step="0.01" name="discount[amount]"
                                        class="form-control" placeholder="Amount"></div>
                                <label class="form-label">Discount Start</label>
                                <div class="col-md-3"><input type="datetime-local" name="discount[starts_at]"
                                        class="form-control"></div>
                                <label class="form-label">Discount End</label>

                                <div class="col-md-3"><input type="datetime-local" name="discount[ends_at]"
                                        class="form-control"></div>
                                </div>
                            </div>

                            <div class="mb-3 col-md-6">
                                <label class="form-label">Final Price</label>
                                <input type="number" step="0.01" class="form-control" name="final_price" required>
                            </div>
                            <div class="mb-3 col-md-6">
                                <label class="form-label">Status</label>
                                <select class="form-select" name="status" required>
                                    <option value="active">Active</option>
                                    <option value="inactive">Inactive</option>
                                    <option value="draft">Draft</option>
                                </select>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Description</label>
                                <textarea class="form-control" name="description" rows="3"></textarea>
                            </div>
                            <div class="mb-3 col-md-12">
                                <label class="form-label">Terms & Conditions</label>
                                <textarea class="form-control" name="tnc" id="tncEditor" rows="4"></textarea>
                            </div>
                        </div>
                        <h5>Package Items</h5>
                        <div id="items-wrapper">
                            <div class="row mb-3 item-row">
                                <div class="col-md-3"><input type="text" name="items[0][title]" class="form-control"
                                        placeholder="Title"></div>
                                <div class="col-md-3"><input type="text" name="items[0][description]" class="form-control"
                                        placeholder="Description"></div>
                                <div class="col-md-2"><input type="number" name="items[0][quantity]" class="form-control"
                                        placeholder="Qty"></div>
                                <div class="col-md-2"><input type="number" step="0.01" name="items[0][unit_price]"
                                        class="form-control" placeholder="Unit Price"></div>
                                <div class="col-md-2"><input type="checkbox" name="items[0][is_optional]" value="1">
                                    Optional</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addItem()">Add Item</button>

                        <h5 class="mt-4">Package Addons</h5>
                        <div id="addons-wrapper">
                            <div class="row mb-3 addon-row">
                                <div class="col-md-4"><input type="text" name="addons[0][name]" class="form-control"
                                        placeholder="Addon Name"></div>
                                <div class="col-md-4"><input type="text" name="addons[0][description]" class="form-control"
                                        placeholder="Description"></div>
                                <div class="col-md-2"><input type="number" step="0.01" name="addons[0][price]"
                                        class="form-control" placeholder="Price"></div>
                                <div class="col-md-2"><input type="checkbox" name="addons[0][is_required]" value="1">
                                    Required</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addAddon()">Add Addon</button>

                        <h5 class="mt-4">Package Inputs</h5>
                        <div id="inputs-wrapper">
                            <div class="row mb-3 input-row">
                                <div class="col-md-3"><input type="text" name="inputs[0][label]" class="form-control"
                                        placeholder="Label"></div>
                                <div class="col-md-2"><input type="text" name="inputs[0][input_key]" class="form-control"
                                        placeholder="Key"></div>
                                <div class="col-md-2">
                                    <select name="inputs[0][input_type]" class="form-select">
                                        <option value="text">Text</option>
                                        <option value="select">Select</option>
                                        <option value="checkbox">Checkbox</option>
                                    </select>
                                </div>
                                <div class="col-md-3"><input type="text" name="inputs[0][options]" class="form-control"
                                        placeholder='Options (JSON)'></div>
                                <div class="col-md-2"><input type="checkbox" name="inputs[0][is_required]" value="1">
                                    Required</div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addInput()">Add Input</button>
                        <h5 class="mt-4">Package Images</h5>
                        <div id="images-wrapper">
                            <div class="row mb-3 image-row">
                                <div class="col-md-4"><input type="text" name="images[0][url]" class="form-control"
                                        placeholder="Image URL"></div>
                                <div class="col-md-4"><input type="text" name="images[0][alt_text]" class="form-control"
                                        placeholder="Alt Text"></div>
                                <div class="col-md-2"><input type="checkbox" name="images[0][is_cover]" value="1"> Cover
                                </div>
                                <div class="col-md-2"><input type="number" name="images[0][sort_order]" class="form-control"
                                        placeholder="Sort"></div>
                            </div>
                        </div>
                        <button type="button" class="btn btn-sm btn-secondary" onclick="addImage()">Add Image</button>

                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script src="https://cdn.tiny.cloud/1/no-api-key/tinymce/6/tinymce.min.js" referrerpolicy="origin"></script>
    <script>
        tinymce.init({
            selector: '#tncEditor',
            height: 300,
            menubar: false,
            plugins: 'advlist autolink lists link charmap preview anchor code fullscreen',
            toolbar: 'undo redo | formatselect | bold italic underline | alignleft aligncenter alignright alignjustify | bullist numlist outdent indent | link code',
            content_style: "body { font-family:Helvetica,Arial,sans-serif; font-size:14px }"
        });
    </script>
    <script>
        let itemIndex = 1, inputIndex = 1, imageIndex = 1, addonIndex = 1;

        function addItem() {
            const html = `<div class="row mb-3 item-row">
                        <div class="col-md-3"><input type="text" name="items[${itemIndex}][title]" class="form-control" placeholder="Title"></div>
                        <div class="col-md-3"><input type="text" name="items[${itemIndex}][description]" class="form-control" placeholder="Description"></div>
                        <div class="col-md-2"><input type="number" name="items[${itemIndex}][quantity]" class="form-control" placeholder="Qty"></div>
                        <div class="col-md-2"><input type="number" step="0.01" name="items[${itemIndex}][unit_price]" class="form-control" placeholder="Unit Price"></div>
                        <div class="col-md-2"><input type="checkbox" name="items[${itemIndex}][is_optional]" value="1"> Optional</div>
                    </div>`;
            document.getElementById('items-wrapper').insertAdjacentHTML('beforeend', html);
            itemIndex++;
        }

        function addInput() {
            const html = `<div class="row mb-3 input-row">
                        <div class="col-md-3"><input type="text" name="inputs[${inputIndex}][label]" class="form-control" placeholder="Label"></div>
                        <div class="col-md-2"><input type="text" name="inputs[${inputIndex}][input_key]" class="form-control" placeholder="Key"></div>
                        <div class="col-md-2">
                            <select name="inputs[${inputIndex}][input_type]" class="form-select">
                                <option value="text">Text</option>
                                <option value="select">Select</option>
                                <option value="checkbox">Checkbox</option>
                            </select>
                        </div>
                        <div class="col-md-3"><input type="text" name="inputs[${inputIndex}][options]" class="form-control" placeholder='Options (JSON)'></div>
                        <div class="col-md-2"><input type="checkbox" name="inputs[${inputIndex}][is_required]" value="1"> Required</div>
                    </div>`;
            document.getElementById('inputs-wrapper').insertAdjacentHTML('beforeend', html);
            inputIndex++;
        }

        function addImage() {
            const html = `<div class="row mb-3 image-row">
                        <div class="col-md-4"><input type="text" name="images[${imageIndex}][url]" class="form-control" placeholder="Image URL"></div>
                        <div class="col-md-4"><input type="text" name="images[${imageIndex}][alt_text]" class="form-control" placeholder="Alt Text"></div>
                        <div class="col-md-2"><input type="checkbox" name="images[${imageIndex}][is_cover]" value="1"> Cover</div>
                        <div class="col-md-2"><input type="number" name="images[${imageIndex}][sort_order]" class="form-control" placeholder="Sort"></div>
                    </div>`;
            document.getElementById('images-wrapper').insertAdjacentHTML('beforeend', html);
            imageIndex++;
        }

        function addAddon() {
            const html = `<div class="row mb-3 addon-row">
                        <div class="col-md-4"><input type="text" name="addons[${addonIndex}][name]" class="form-control" placeholder="Addon Name"></div>
                        <div class="col-md-4"><input type="text" name="addons[${addonIndex}][description]" class="form-control" placeholder="Description"></div>
                        <div class="col-md-2"><input type="number" step="0.01" name="addons[${addonIndex}][price]" class="form-control" placeholder="Price"></div>
                        <div class="col-md-2"><input type="checkbox" name="addons[${addonIndex}][is_required]" value="1"> Required</div>
                    </div>`;
            document.getElementById('addons-wrapper').insertAdjacentHTML('beforeend', html);
            addonIndex++;
        }
    </script>


@endpush
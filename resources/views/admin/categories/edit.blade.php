@extends('admin.layouts.app')

@section('content')
    <div class="card">
        <div class="card-header">
            <h4 class="card-title">Add Product Photo</h4>
        </div>
        <div class="card-body">
            <!-- File Upload -->
            <form action="" method="post" class="dropzone" id="myAwesomeDropzone" data-plugin="dropzone"
                data-previews-container="#file-previews" data-upload-preview-template="#uploadPreviewTemplate">
                <div class="fallback">
                    <input name="file" type="file" />
                </div>
                <div class="dz-message needsclick">
                    <i class="bx bx-cloud-upload fs-48 text-primary"></i>
                    <h3 class="mt-4">Drop your images here, or <span class="text-primary">click to browse</span></h3>
                    {{-- <span class="text-muted fs-13">
                        1600 x 1200 (4:3) recommended. PNG, JPG and GIF files are allowed
                    </span> --}}
                </div>
            </form>
            <span class="text-muted fs-13">Max upload file size 2 MB</span>
        </div>
    </div>

    <form id="categoryForm">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Edit Category Information</h4>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="name" class="form-label">Category Name</label>
                            <input type="text" id="name" name="name" class="form-control"
                                placeholder="Category Name" value="{{ $category->name }}" required>
                        </div>
                    </div>
                    <div class="col-lg-6">
                        <div class="mb-3">
                            <label for="slug" class="form-label">Category Slug</label>
                            <input type="text" id="slug" name="slug" class="form-control"
                                placeholder="Category Slug" value="{{ $category->slug }}">
                        </div>
                    </div>
                </div>
                <div class="row">
                    <div class="col-lg-12">
                        <div class="mb-3">
                            <label for="description" class="form-label">Description</label>
                            <textarea class="form-control bg-light-subtle" id="description" rows="5" placeholder="Category Description"
                                name="description">{{ $category->description }}</textarea>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="p-3 bg-light mb-3 rounded">
            <div class="row justify-content-end g-2">
                <div class="col-lg-2">
                    <button type="submit" class="btn btn-outline-secondary w-100">Update Category</button>
                </div>
            </div>
        </div>
    </form>
@endsection


@section('javascript')
    <script>
        Dropzone.autoDiscover = false;
        const myDropzone = new Dropzone("#myAwesomeDropzone", {
            url: "{{ route('category.media.store', $category->id) }}",
            autoProcessQueue: false,
            maxFilesize: 2,
            maxFiles: 1,
            uploadMultiple: false,
            parallelUploads: 1,
            acceptedFiles: ".jpg,.jpeg,.png",
            addRemoveLinks: true,
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
            },
            init: function() {
                var myDropzone = this;

                // Existing images
                @if ($category->media)
                    @foreach ($category->media as $media)
                        var mockFile = {
                            name: "{{ $media->file_name }}",
                            size: {{ $media->size }},
                            id: "{{ $media->id }}"
                        };

                        mockFile.status = Dropzone.SUCCESS;
                        mockFile.accepted = true;

                        myDropzone.emit("addedfile", mockFile);
                        myDropzone.emit("thumbnail", mockFile, "{{ $media->getUrl() }}");
                        myDropzone.emit("complete", mockFile);
                    @endforeach
                @endif

                this.on("addedfile", function(file) {
                    // If there are more than 1 file (including the new one), remove the old ones
                    if (this.files.length > 1) {
                        this.removeFile(this.files[0]);
                    }
                });

                this.on("removedfile", function(file) {
                    // Only delete from server if this was an explicit removal (no other files left)
                    // If we are replacing (files.length > 0), don't delete the old one via AJAX yet.
                    // The new upload will handle the replacement via singleFile() on backend.
                    if (this.files.length === 0 && file.id) {
                        $.ajax({
                            type: 'DELETE',
                            url: '/admin/category/media/' + file.id,
                            headers: {
                                'X-CSRF-TOKEN': $('meta[name="csrf-token"]')
                                    .attr('content')
                            },
                            success: function(data) {
                                console.log("File has been successfully removed!!");
                            },
                            error: function(e) {
                                console.log(e);
                            }
                        });
                    }
                });
            }
        });
        myDropzone.on("queuecomplete", function() {
            if (myDropzone.getUploadingFiles().length === 0 &&
                myDropzone.getQueuedFiles().length === 0 &&
                myDropzone.getAcceptedFiles().length > 0) {

                window.location.href = "{{ route('category.index') }}";
            }
        });




        $(document).ready(function() {

            $('#categoryForm').on('submit', function(e) {
                e.preventDefault();

                let form = this;
                let formData = new FormData(form);
                formData.append('_method', 'PUT');

                $.ajax({
                    url: "{{ route('category.update', $category->id) }}",
                    type: "POST",
                    data: formData,
                    processData: false,
                    contentType: false,
                    headers: {
                        'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
                    },
                    beforeSend: function() {
                        // Clear previous errors
                        $('#categoryForm .is-invalid').removeClass('is-invalid');
                        $('#categoryForm .invalid-feedback').remove();
                    },
                    success: function(response) {
                        if (myDropzone.getQueuedFiles().length > 0) {
                            myDropzone.processQueue();
                        } else {
                            window.location.href = "{{ route('category.index') }}";
                        }
                    },
                    error: function(xhr) {
                        if (xhr.status === 422) {
                            let errors = xhr.responseJSON.errors;
                            $.each(errors, function(key, value) {
                                let input = $(`[name="${key}"]`);
                                if (input.length > 0) {
                                    input.addClass('is-invalid');
                                    if (input.next('.invalid-feedback').length === 0) {
                                        input.after(
                                            `<div class="invalid-feedback">${value[0]}</div>`
                                        );
                                    } else {
                                        input.next('.invalid-feedback').text(value[0]);
                                    }
                                }
                            });
                        } else {
                            alert("Something went wrong. Check server logs.");
                        }
                    }
                });

            });

        });
        slugGenerator('#name', '#slug');
    </script>
@endsection

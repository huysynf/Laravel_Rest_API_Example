@extends('admin.layout.app')
@section('content')
    <div class="container">
        <h1>List Post</h1>
        <button class="btn btn-primary btn-create" >Create Post</button>
        <div class="table-post" id="postList" data-list-action="{{route('api.posts.index')}}">
            <table class="table table-hover" id="postTable">
                <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Title</th>
                    <th scope="col">Body</th>
                    <th scope="col">Action</th>
                </tr>
                </thead>
                <tbody id="postTableBody">

                </tbody>
            </table>
        </div>

        <div id="paginate">

        </div>


        <div class="modal fade" id="editPostModal" tabindex="-1" aria-labelledby="editPostModalLabel"
             aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostModalLabel">Edit Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="name" class="form-control" id="nameEditInput" aria-describedby="emailHelp">
                            <div class="error error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Body</label>
                            <textarea class="form-control" id="bodyEditInput" aria-describedby="emailHelp">
                                </textarea>
                            <div class="error error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-update">Update</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="createPostModal" tabindex="-1" aria-labelledby="createPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostModalLabel">Create Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="name" class="form-label">Name</label>
                            <input type="name" class="form-control" id="createNameInput" aria-describedby="emailHelp">
                            <div class="error error"></div>
                        </div>
                        <div class="mb-3">
                            <label for="name" class="form-label">Body</label>
                            <textarea class="form-control" id="createBodyInput" aria-describedby="emailHelp">
                                </textarea>
                            <div class="error error"></div>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
                        <button type="button" class="btn btn-primary btn-store" data-action="{{route('api.posts.store')}}">Create</button>
                    </div>
                </div>
            </div>
        </div>

        <div class="modal fade" id="showPostModal" tabindex="-1" aria-labelledby="showPostModalLabel" aria-hidden="true">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="editPostModalLabel">shoq Post</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                       <div class="d-flex">
                           <p>Name: <span id="showName"></span></p>
                           <p>Body: <span id="showBody"></span></p>
                       </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
@endsection
@section('script')
    <script>
        $(function () {
            let editModal = new bootstrap.Modal(document.getElementById('editPostModal'), {})
            let createModal = new bootstrap.Modal(document.getElementById('createPostModal'), {})
            let showModal = new bootstrap.Modal(document.getElementById('showPostModal'), {})
            let updateUrl = "";

            getList()

            function getList() {
                let urlList = $('#postList').data('list-action')
                list(urlList);
            }

            function list(url) {
                $.get(url, res => {
                    let posts = res?.data?.data;
                    let meta = res?.data?.meta;
                    let postHtml = renderPostsHtml(posts)
                    $('#postTableBody').html(postHtml)

                    let paginationHtml = renderPaginationHtml(meta);
                    $('#paginate').html(paginationHtml)
                })
            }

            function renderPostsHtml(posts) {
                let postHtml = "";
                if (posts && posts.length > 0) {
                    for (let post of posts) {
                        postHtml += `
                        <tr id="post-${post.id}">
                            <th scope="row">${post.id}</th>
                            <td>${post.name}</td>
                            <td>${post.body ?? ''}</td>
                            <td>

                                <button class="btn btn-primary btn-show" data-action="${post.show_url}">Show</button>
                                <button class="btn btn-warning btn-edit" data-action-edit="${post.show_url}" data-action-update="${post.update_url}">Edit</button>
                                <button class="btn btn-danger btn-delete" data-action="${post.delete_url}">Delete</button>
                            </td>
                        </tr>
                       `;
                    }
                } else {
                    postHtml += `
                        <tr>
                           No data.
                        </tr>
                       `;
                }
                return postHtml;
            }

            function renderPaginationHtml(meta) {
                let result = `<nav aria-label="Page navigation example">
                   <ul class="pagination">`;

                for (let link of meta.links) {
                    result += `<li class="page-item  ${link.active ? 'active' : ''}"><a class="page-link" href="${link.url}">
                                <span aria-hidden="true">${link.label}</span>

                    </a></li>`;

                }
                result += ` </ul>
               </nav>`;

                return result;
            }

            $(document).on('click', '.page-link', function (e) {
                e.preventDefault();
                let url = $(this).attr('href');
                list(url)
            })

            $(document).on('click', '.btn-edit', function (e) {
                e.preventDefault();
                updateUrl = $(this).data('action-update');
                let editPostUrl =  $(this).data('action-edit');

                $.get(editPostUrl, res => {
                    let post  = res?.data;
                    $('#nameEditInput').val(post.name)
                    $('#bodyEditInput').val(post.body)
                    editModal.show();

                })
            })

            $(document).on('click', '.btn-update', function (e) {
                e.preventDefault();
               let data = {
                   name :  $('#nameEditInput').val(),
                   body: $('#bodyEditInput').val()
               }
                $.ajax({
                    url: updateUrl,
                    data,
                    method: 'PUT'
                }).then(res => {
                    let post = res.data;
                    let postHtml =  renderPostHtml(post);
                    $(`#post-${post.id}`).replaceWith(postHtml)
                    editModal.hide();
                })
            })

            $(document).on('click', '.btn-update', function (e) {
                e.preventDefault();
                let data = {
                    name :  $('#nameEditInput').val(),
                    body: $('#bodyEditInput').val()
                }
                $.ajax({
                    url: updateUrl,
                    data,
                    method: 'PUT'
                }).then(res => {
                    let post = res.data;
                    let postHtml =  renderPostHtml(post);
                    $(`#post-${post.id}`).replaceWith(postHtml)
                    editModal.hide();
                })
            })
            $(document).on('click', '.btn-create', function (e) {
                e.preventDefault();
                editModal.hide()
                createModal.show()
                resetErrors()
            })

            $(document).on('click', '.btn-store', function (e) {
                e.preventDefault();
                let url = $(this).data('action')
                let data = {
                    name :  $('#createNameInput').val(),
                    body: $('#createBodyInput').val()
                }
                $.ajax({
                    url: url,
                    data,
                    method: 'POST'
                }).then(res => {
                    createModal.hide()
                    getList()

                })
            })
            $(document).on('click', '.btn-show', function (e) {
                e.preventDefault();
                let url = $(this).data('action')
                $.get(url, res => {
                    let post = res.data.data;
                    $('#showName').html(post.name)
                    $('#showBody').html(post.body)
                    showModal.show()
                })
            })

            $(document).on('click', '.btn-delete', function (e) {
                e.preventDefault();
                let url = $(this).data('action')
               if (confirm('delete'))
               {
                   $.ajax({
                       url: url,
                       data:{},
                       method: 'DELETE'
                   }).then(res => {
                       getList()

                   })
               }

            })


            function resetErrors() {
                $('.errors').html('')
            }

            function  renderPostHtml(post)
            {
                return ` <tr id="post-${post.id}">
                            <th scope="row">${post.id}</th>
                            <td>${post.name}</td>
                            <td>${post.body ?? ''}</td>
                            <td>

                                <button class="btn btn-primary btn-show" data-action="${post.show_url}">Show</button>
                                <button class="btn btn-warning btn-edit" data-action-edit="${post.show_url}" data-action-update="${post.update_url}">Edit</button>
                                <button class="btn btn-danger btn-delete" data-action="${post.delete_url}">Delete</button>
                            </td>
                        </tr>`;
            }

        })
    </script>
@endsection

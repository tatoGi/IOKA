@extends('admin.layouts.app')

@section('content')
    <div class="container my-5">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h3 class="h3 font-weight-bold text-center">Pages</h3>

            <a href="/ioka_admin/menu/create" class="btn btn-primary btn-sm position-relative" style="font-size: 0.8rem;">

                <i class="material-icons-outlined ml-2">Create Page</i>
            </a>
        </div>

        <!-- Nestable Container -->
        <div class="dd w-100" id="nestable" data-route="/pages/arrange">
            @include('admin.pages.list', ['pages' => $pages ?? []])
        </div>
        <!-- End Nestable Container -->

    </div>
@endsection

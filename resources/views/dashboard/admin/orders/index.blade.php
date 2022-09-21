@extends('dashboard.admin.layouts.app')

@section('content')

    <div>
        <h2>@lang('orders.orders')</h2>
    </div>

    <ul class="breadcrumb mt-2">
        <li class="breadcrumb-item"><a href="{{ route('dashboard.admin.home') }}">@lang('site.home')</a></li>
        <li class="breadcrumb-item">@lang('orders.orders')</li>
    </ul>

    <div class="row">

        <div class="col-md-12">

            <div class="tile shadow">

                <div class="row mb-2">

                    <div class="col-md-12">

                        @if (auth()->user()->hasPermission('orders_read'))
                            <a href="{{ route('dashboard.admin.orders.create') }}" class="btn btn-primary"><i class="fa fa-plus"></i> @lang('site.create')</a>
                        @endif

                        @if (auth()->user()->hasPermission('delete_orders'))
                            <form method="post" action="{{ route('dashboard.admin.orders.bulk_delete') }}" style="display: inline-block;">
                                @csrf
                                @method('delete')
                                <input type="hidden" name="record_ids" id="record-ids">
                                <button type="submit" class="btn btn-danger" id="bulk-delete" disabled="true"><i class="fa fa-trash"></i> @lang('site.bulk_delete')</button>
                            </form><!-- end of form -->
                        @endif

                    </div>

                </div><!-- end of row -->

                <div class="row">

                    <div class="col-md-6">
                        <div class="form-group">
                            <input type="text" id="data-table-search" class="form-control" autofocus placeholder="@lang('site.search')">
                        </div>
                    </div>

                    <div class="col-md-6">
                        <div class="form-group">
                            <div class="form-group">
                                <select id="categorey" class="form-control select2">
                                    <option value="">@lang('site.all') @lang('orders.category')</option>
                                    @foreach ($categoreys as $categorey)
                                        <option value="{{ $categorey->name }}">{{ $categorey->name }}</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                </div><!-- end of row -->

                <div class="row">

                    <div class="col-md-12">

                        <div class="table-responsive">

                            <table class="table datatable" id="orders-table" style="width: 100%;">
                                <thead>
                                <tr>
                                    <th>
                                        <div class="animated-checkbox">
                                            <label class="m-0">
                                                <input type="checkbox" id="record__select-all">
                                                <span class="label-text"></span>
                                            </label>
                                        </div>
                                    </th>
                                    <th>@lang('site.DT_RowIndex')</th>
                                    <th>@lang('orders.user')</th>
                                    <th>@lang('orders.category')</th>
                                    <th>@lang('site.created_at')</th>
                                    <th>@lang('site.action')</th>
                                </tr>
                                </thead>
                            </table>

                        </div><!-- end of table responsive -->

                    </div><!-- end of col -->

                </div><!-- end of row -->

            </div><!-- end of tile -->

        </div><!-- end of col -->

    </div><!-- end of row -->

@endsection

@push('scripts')

    <script>

        let role;

        let ordersTable = $('#orders-table').DataTable({
            dom: "tiplr",
            scrollY: '500px',
            scrollCollapse: true,
            sScrollX: "100%",
            serverSide: true,
            processing: true,
            "language": {
                "url": "{{ asset('admin_assets/datatable-lang/' . app()->getLocale() . '.json') }}"
            },
            ajax: {
                url: '{{ route('dashboard.admin.orders.data') }}',
                data: function (d) {
                    d.role_id = role;
                }
            },
            columns: [
                {data: 'record_select', name: 'record_select', searchable: false, sortable: false, width: '1%'},
                {data: 'DT_RowIndex', name: 'DT_RowIndex'},
                {data: 'user', name: 'user'},
                {data: 'category', name: 'category'},
                {data: 'created_at', name: 'created_at', searchable: false},
                {data: 'actions', name: 'actions', searchable: false, sortable: false, width: '20%'},
            ],
            order: [[4, 'desc']],
            drawCallback: function (settings) {
                $('.record__select').prop('checked', false);
                $('#record__select-all').prop('checked', false);
                $('#record-ids').val();
                $('#bulk-delete').attr('disabled', true);
            }
        });

        $('#data-table-search').keyup(function () {
            ordersTable.search(this.value).draw();
        })

        $('#categorey').on('change', function () {
            // ordersTable.search(this.value).draw();
        })
    </script>

@endpush
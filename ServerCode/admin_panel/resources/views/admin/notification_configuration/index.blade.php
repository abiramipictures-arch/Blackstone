@extends('admin.layout.page-app')
@section('page_title', __('label.notification_configuration'))
@section('tab_title', __('label.notification_configuration'))

@section('content')
    @include('admin.layout.sidebar')

    <div class="right-content">
        @include('admin.layout.header')

        <div class="body-content">
            <!-- Mobile page title -->
            <h1 class="page-title-sm">{{__('label.notification_configuration')}}</h1>

            <!-- Breadcrumb -->
            <div class="row">
                <div class="col-sm-12">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="{{ route('admin.dashboard') }}">{{__('label.dashboard')}}</a></li>
                        <li class="breadcrumb-item active" aria-current="page">{{__('label.notification_configuration')}}</li>
                    </ol>
                </div>
            </div>

            <div class="card custom-border-card">
                <div class="card-header d-flex align-items-center justify-content-between">
                    <span><i class="fa-solid fa-bell"></i>{{__('label.notification_configuration')}}</span>
                    <div class="d-flex align-items-center">
                        <span class="mr-3" style="font-size:16px;">{{__('label.do_you_want_to_disable_all_notifications')}}</span>
                        <label id="notificationToggle" class="status-toggle {{ $main_status == 1 ? 'status-on' : 'status-off' }}" onclick="toggleMainStatus(this)">
                            <span class="status-toggle-track"><span class="status-toggle-thumb"></span></span>
                        </label>
                    </div>
                </div>

                <div class="card-body">
                    <div class="table-responsive" id="dataTable-container">
                        <table class="table table-striped table-bordered" id="datatable">
                            <thead>
                                <tr>
                                    <th>{{__('label.#')}}</th>
                                    <th>{{__('label.type')}}</th>
                                    <th>{{__('label.notification')}}</th>
                                    <th>{{__('label.mail')}}</th>
                                </tr>
                            </thead>
                            <tbody></tbody>
                        </table>
                    </div>
                </div>

                <div class="card-footer" id="saveButtonWrapper" style="{{ $main_status == 1 ? '' : 'display:none;' }}">
                    <button id="saveButton" class="btn btn-default mw-120">
                        <i class="fa-solid fa-floppy-disk fa-lg mr-2"></i>{{ __('label.save') }}
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@section('pagescript')
    <script>
        var mainstatus = '{{ $main_status }}';

        $(document).ready(function() {
            var table = $('#datatable').DataTable({
                ...dataTableDefaults,
                lengthMenu: [
                    [15, 100, 500, -1],
                    [15, 100, 500, 'All']
                ],
                ajax: {
                    url: '{{ route("admin.notificationconfiguration.index") }}',
                },
                columns: [
                    { data: 'DT_RowIndex', name: 'DT_RowIndex', orderable: false, searchable: false },
                    {
                        data: 'type', name: 'type', orderable: false, searchable: false,
                        render: function(data) {
                            return data ? data : '-';
                        }
                    },
                    {
                        data: 'send_notification', name: 'send_notification', orderable: false, searchable: false,
                        render: function(data, type, row) {
                            var isDisabled = (row.type == 'login' || row.type == 'package_expired_notice' || row.type == 'rent_expired_notice');
                            var state      = data == 1 ? 'status-on' : 'status-off';
                            var style      = isDisabled ? 'style="pointer-events:none;opacity:0.4;"' : '';
                            return `<label class="status-toggle ${state} notif-toggle" data-id="${row.id}" ${style} onclick="toggleSwitch(this)">
                                        <span class="status-toggle-track"><span class="status-toggle-thumb"></span></span>
                                    </label>`;
                        },
                    },
                    {
                        data: 'send_mail', name: 'send_mail', orderable: false, searchable: false,
                        render: function(data, type, row) {
                            var isDisabled = (row.type == 'login' || row.type == 'package_buy' || row.type == 'package_expired_notice' || row.type == 'rent_buy' || row.type == 'rent_expired_notice');
                            var state      = data == 1 ? 'status-on' : 'status-off';
                            var style      = isDisabled ? 'style="pointer-events:none;opacity:0.4;"' : '';
                            return `<label class="status-toggle ${state} mail-toggle" data-id="${row.id}" ${style} onclick="toggleSwitch(this)">
                                        <span class="status-toggle-track"><span class="status-toggle-thumb"></span></span>
                                    </label>`;
                        },
                    },
                ],
            });

            if (mainstatus != 1) {
                $('#dataTable-container').hide();
            }
        });

        function toggleSwitch(el) {
            $(el).toggleClass('status-on status-off');
        }

        function SaveNotification(type, status) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            let entries = [];
            $('#datatable tbody tr').each(function() {
                let entryId      = $(this).find('.notif-toggle').data('id');
                let notification = $(this).find('.notif-toggle').hasClass('status-on') ? 1 : 0;
                let email        = $(this).find('.mail-toggle').hasClass('status-on') ? 1 : 0;
                entries.push({ id: entryId, notification: notification, email: email });
            });

            $("#dvloader").show();
            $.ajax({
                url: '{{ route("admin.notificationconfiguration.store") }}',
                type: 'POST',
                data: {
                    entries : entries,
                    type    : type,
                    status  : status,
                    _token  : '{{ csrf_token() }}'
                },
                success: function(resp) {
                    $("#dvloader").hide();
                    get_responce_message(resp, '', '{{ route("admin.notificationconfiguration.index") }}');
                },
                error: function(XMLHttpRequest, textStatus, errorThrown) {
                    $("#dvloader").hide();
                    toastr.error(errorThrown, textStatus);
                }
            });
        }

        $('#saveButton').on('click', function() {
            SaveNotification('', 2);
        });

        function toggleMainStatus(el) {
            if ('{{ Demo_Mode() }}' != 1) {
                showError();
                return;
            }

            var isOn = $(el).hasClass('status-on');
            if (isOn) {
                $(el).removeClass('status-on').addClass('status-off');
                $('#dataTable-container').hide();
                $('#saveButtonWrapper').hide();
                SaveNotification('all', 0);
            } else {
                $(el).removeClass('status-off').addClass('status-on');
                $('#dataTable-container').show();
                $('#saveButtonWrapper').show();
                SaveNotification('all', 1);
            }
        }
    </script>
@endsection

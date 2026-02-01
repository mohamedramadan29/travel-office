@extends('admin.layouts.app')
@section('title', 'كشف شامل للعملاء')
@section('css')
<link rel="stylesheet" href="https://cdn.datatables.net/2.2.2/css/dataTables.bootstrap5.min.css">
<style>
    .dt-layout-row {
        display: flex;
        justify-content: space-between;
    }

    #printButton {
        margin-bottom: 20px;
    }

    .table-responsive {
        margin-top: 20px;
    }
</style>
@endsection
@section('css')
<style>
    .dt-layout-row {
        display: flex;
        justify-content: space-between;
    }

    #printButton {
        margin-bottom: 20px;
    }

    .table-responsive {
        margin-top: 20px;
    }
</style>
@endsection
@section('content')
<div class="app-content content">
    <div class="content-wrapper">
        <div class="content-header row">
            <div class="mb-2 content-header-left col-md-6 col-12 breadcrumb-new">
                <h3 class="mb-0 content-header-title d-inline-block">كشف شامل للعملاء</h3>
                <div class="row breadcrumbs-top d-inline-block">
                    <div class="breadcrumb-wrapper col-12">
                        <ol class="breadcrumb">
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.welcome') }}">الرئيسية</a></li>
                            <li class="breadcrumb-item"><a href="{{ route('dashboard.clients.index') }}">العملاء</a>
                            </li>
                            <li class="breadcrumb-item active">كشف شامل للعملاء</li>
                        </ol>
                    </div>
                </div>
            </div>
            <div class="content-header-right col-md-6 col-12">
                <a href="#" id="exportPdfBtn" class="btn btn-danger float-end ms-2" target="_blank">PDF تصدير</a>
                <a href="#" id="exportExcelBtn" class="btn btn-success float-end" target="_blank">Excel تصدير</a>
            </div>
        </div>
        <div class="content-body">
            <!-- Filter Section Removed -->

            <!-- Bordered striped start -->
            <div class="row">
                <div class="col-12">
                    <div class="card">
                        <div class="card-content collapse show">
                            <div class="card-body">
                                <div id="reportContent" class="table-responsive">
                                    <table class="table table-bordered table-striped" id="clientsTable">
                                        <thead>
                                            <tr>
                                                <th>
                                                    <input type="checkbox" id="selectAll">
                                                </th>
                                                <th>#</th>
                                                <th>الاسم</th>
                                                <th>الرصيد</th>
                                                <th>دائن / مدين</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            @forelse ($clients as $client)
                                            <tr>
                                                <td>
                                                    <input type="checkbox" class="client-checkbox" value="{{ $client->id }}">
                                                </td>
                                                <th scope="row">{{ $loop->iteration }}</th>
                                                <td>{{ $client->name }}</td>
                                                <td>{{ number_format($client->balance(), 2) }} د.ل</td>
                                                <td>{{ $client->balance() > 0 ? 'مدين' : ($client->balance() < 0
                                                        ? 'دائن' : '' ) }}</td>
                                            </tr>
                                            @empty
                                            <tr>
                                                <td colspan="4" class="text-center">لا يوجد بيانات</td>
                                            </tr>
                                            @endforelse
                                        </tbody>
                                    </table>
                                    {{ $clients->links() }}
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <!-- Bordered striped end -->
        </div>
    </div>
</div>
@endsection
@section('js')
<script>
    $(document).ready(function() {
        // Select All Checkbox
        $('#selectAll').click(function() {
            $('.client-checkbox').prop('checked', this.checked);
            updateExportLinks();
        });

        // Individual Checkbox Click
        $('.client-checkbox').click(function() {
            if ($('.client-checkbox:checked').length == $('.client-checkbox').length) {
                $('#selectAll').prop('checked', true);
            } else {
                $('#selectAll').prop('checked', false);
            }
            updateExportLinks();
        });

        // Update Export Links with selected clients
        function updateExportLinks() {
            var selectedClients = [];
            $('.client-checkbox:checked').each(function() {
                selectedClients.push($(this).val());
            });

            var pdfLink = "{{ route('dashboard.clients.report.pdf') }}";
            var excelLink = "{{ route('dashboard.clients.report.excel') }}";

            if (selectedClients.length > 0) {
                var params = 'client_ids=' + selectedClients.join(',');
                $('#exportPdfBtn').attr('href', pdfLink + '?' + params);
                $('#exportExcelBtn').attr('href', excelLink + '?' + params);
            } else {
                $('#exportPdfBtn').attr('href', pdfLink);
                $('#exportExcelBtn').attr('href', excelLink);
            }
        }
    });
</script>
@endsection

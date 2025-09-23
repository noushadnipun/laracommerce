@extends('admin.layouts.master')

@section('site-title')
Bulk Product Import
@endsection

@section('page-title')
Bulk Product Import from Excel
@endsection

@section('page-content')

<!-- Main Import Section -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header bg-primary text-white">
                <h3 class="card-title mb-0">
                    <i class="fas fa-upload"></i> Bulk Product Import
                </h3>
            </div>
            <div class="card-body">
                <!-- Step 1: Download Template -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-success">
                            <h5><i class="fas fa-download"></i> Step 1: Download Excel Template</h5>
                            <p class="mb-3">Download the Excel template with sample data and proper format. Fill your product data following the sample format.</p>
                            <a href="{{ route('admin_product_import_template') }}" class="btn btn-success btn-lg">
                                <i class="fas fa-download"></i> Download Excel Template
                            </a>
                        </div>
                    </div>
                </div>

                <!-- Step 2: Upload Form -->
                <div class="row mb-4">
                    <div class="col-12">
                        <div class="alert alert-info">
                            <h5><i class="fas fa-upload"></i> Step 2: Upload ZIP File</h5>
                            <p class="mb-3">Create a ZIP file containing your Excel file and images folder, then upload it below.</p>
                            
                            <form action="{{ route('admin_product_import_store') }}" method="POST" enctype="multipart/form-data" id="importForm">
                                @csrf
                                
                                <div class="form-group">
                                    <label for="import_file">Select ZIP File</label>
                                    <div class="input-group">
                                        <div class="custom-file">
                                            <input type="file" class="custom-file-input" id="import_file" name="import_file" accept=".zip" required>
                                            <label class="custom-file-label" for="import_file">Choose ZIP file...</label>
                                        </div>
                                        <div class="input-group-append">
                                            <button type="submit" class="btn btn-primary" id="importBtn">
                                                <i class="fas fa-upload"></i> Import Products
                                            </button>
                                        </div>
                                    </div>
                                    <small class="form-text text-muted">
                                        <i class="fas fa-info-circle"></i> 
                                        Maximum file size: 10MB. ZIP should contain Excel/CSV file and images folder.
                                    </small>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>

                <!-- Progress Bar -->
                <div class="row" id="progressSection" style="display: none;">
                    <div class="col-12">
                        <div class="alert alert-warning">
                            <h5><i class="fas fa-spinner fa-spin"></i> Processing Import...</h5>
                            <div class="progress mb-2">
                                <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%" id="progressBar"></div>
                            </div>
                            <div class="row">
                                <div class="col-md-3">
                                    <small>Progress: <span id="progressText">0%</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Processed: <span id="processedText">0</span> / <span id="totalText">0</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Successful: <span id="successfulText" class="text-success">0</span></small>
                                </div>
                                <div class="col-md-3">
                                    <small>Failed: <span id="failedText" class="text-danger">0</span></small>
                                </div>
                            </div>
                            <p class="mb-0 mt-2">Please wait while we process your import...</p>
                        </div>
                    </div>
                </div>

                <!-- Import Jobs History -->
                <div class="row mt-4" id="importHistory" style="display: none;">
                    <div class="col-12">
                        <div class="card">
                            <div class="card-header">
                                <h5 class="card-title mb-0">
                                    <i class="fas fa-history"></i> Import History
                                </h5>
                            </div>
                            <div class="card-body">
                                <div class="table-responsive">
                                    <table class="table table-sm" id="importJobsTable">
                                        <thead>
                                            <tr>
                                                <th>File</th>
                                                <th>Status</th>
                                                <th>Progress</th>
                                                <th>Processed</th>
                                                <th>Successful</th>
                                                <th>Failed</th>
                                                <th>Created</th>
                                                <th>Actions</th>
                                            </tr>
                                        </thead>
                                        <tbody>
                                            <!-- Dynamic content -->
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
</div>

<!-- Quick Tips Section -->
<div class="row mb-4">
    <div class="col-12">
        <div class="card bg-info text-white">
            <div class="card-header">
                <h3 class="card-title mb-0">
                    <i class="fas fa-lightbulb"></i> Quick Tips & Instructions
                </h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <h5><i class="fas fa-download"></i> Step 1: Download Template</h5>
                        <ul class="mb-3">
                            <li>Click "Download Template" button above</li>
                            <li>Open the CSV file in Excel or Google Sheets</li>
                            <li>Follow the sample data format</li>
                        </ul>
                        
                        <h5><i class="fas fa-edit"></i> Step 2: Fill Your Data</h5>
                        <ul class="mb-3">
                            <li><strong>Title:</strong> Product name (required)</li>
                            <li><strong>Code:</strong> Unique SKU (required)</li>
                            <li><strong>Description:</strong> Full product description</li>
                            <li><strong>Prices:</strong> Regular, Sale, Purchase prices</li>
                            <li><strong>Stock:</strong> Total available quantity</li>
                        </ul>
                    </div>
                    <div class="col-md-6">
                        <h5><i class="fas fa-images"></i> Step 3: Prepare Images</h5>
                        <ul class="mb-3">
                            <li>Create a folder named "images"</li>
                            <li>Put all product images in this folder</li>
                            <li>Use only filenames in Excel (e.g., "product1.jpg")</li>
                            <li>Supported formats: JPG, PNG, GIF</li>
                        </ul>
                        
                        <h5><i class="fas fa-upload"></i> Step 4: Upload & Import</h5>
                        <ul class="mb-0">
                            <li>Create a ZIP file with Excel + images folder</li>
                            <li>Upload the ZIP file above</li>
                            <li>Wait for processing to complete</li>
                            <li>Check results and errors</li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Field Reference Section -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-success text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-check-circle"></i> Required Fields
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Title:</strong> Product name</li>
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Code:</strong> Unique SKU</li>
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Description:</strong> Product details</li>
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Regular_Price:</strong> Original price</li>
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Total_Stock:</strong> Available quantity</li>
                    <li><i class="fas fa-asterisk text-danger"></i> <strong>Category_ID:</strong> Category number</li>
                </ul>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header bg-warning text-white">
                <h5 class="card-title mb-0">
                    <i class="fas fa-info-circle"></i> Optional Fields
                </h5>
            </div>
            <div class="card-body">
                <ul class="list-unstyled mb-0">
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Sale_Price:</strong> Discounted price</li>
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Purchase_Price:</strong> Cost price</li>
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Brand_ID:</strong> Brand number</li>
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Shipping_Type:</strong> 0=Free, 1=Flat Rate</li>
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Images:</strong> Featured & Gallery images</li>
                    <li><i class="fas fa-circle text-secondary"></i> <strong>Remote_Images:</strong> Image URLs</li>
                </ul>
            </div>
        </div>
    </div>
</div>

<!-- Category & Brand Reference -->
<div class="row mb-4">
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-tags"></i> Category Reference
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($categories as $category)
                            <tr>
                                <td>{{ $category->id }}</td>
                                <td>{{ $category->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    
    <div class="col-md-6">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-star"></i> Brand Reference
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Name</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($brands as $brand)
                            <tr>
                                <td>{{ $brand->id }}</td>
                                <td>{{ $brand->name }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Sample Data Preview -->
<div class="row">
    <div class="col-12">
        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="fas fa-table"></i> Sample Data Format
                </h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-sm">
                        <thead class="bg-light">
                            <tr>
                                <th>Title</th>
                                <th>Code</th>
                                <th>Description</th>
                                <th>Regular_Price</th>
                                <th>Sale_Price</th>
                                <th>Category_ID</th>
                                <th>Brand_ID</th>
                                <th>Total_Stock</th>
                                <th>Featured_Image</th>
                                <th>Gallery_Images</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>iPhone 15 Pro</td>
                                <td>IPH15P</td>
                                <td>Latest iPhone Pro</td>
                                <td>1200</td>
                                <td>1100</td>
                                <td>1</td>
                                <td>1</td>
                                <td>50</td>
                                <td>iphone15pro.jpg</td>
                                <td>iphone15_1.jpg,iphone15_2.jpg</td>
                            </tr>
                            <tr>
                                <td>Samsung Galaxy S24</td>
                                <td>SAMS24</td>
                                <td>Samsung flagship</td>
                                <td>1000</td>
                                <td>950</td>
                                <td>1</td>
                                <td>2</td>
                                <td>30</td>
                                <td>samsung_s24.jpg</td>
                                <td>samsung_1.jpg,samsung_2.jpg</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@section('cusjs')
<script>
let currentImportJob = null;
let progressInterval = null;

$(document).ready(function() {
    // File input change
    $('#import_file').on('change', function() {
        var fileName = $(this).val().split('\\').pop();
        $(this).next('.custom-file-label').html(fileName);
    });
    
    // Form submission
    $('#importForm').on('submit', function(e) {
        var fileInput = $('#import_file')[0];
        
        if (!fileInput.files.length) {
            alert('Please select a ZIP file to upload.');
            e.preventDefault();
            return;
        }
        
        // Show progress section
        $('#progressSection').show();
        $('#importBtn').prop('disabled', true).html('<i class="fas fa-spinner fa-spin"></i> Queuing...');
        
        // Load import history
        loadImportHistory();
    });
    
    // Load import history on page load
    loadImportHistory();
    
    // Auto-dismiss alerts
    setTimeout(function() {
        $('.alert').fadeOut();
    }, 5000);
});

function loadImportHistory() {
    $.ajax({
        url: '{{ route("admin_product_import_jobs") }}',
        method: 'GET',
        success: function(response) {
            if (response.data && response.data.length > 0) {
                $('#importHistory').show();
                updateImportHistoryTable(response.data);
                
                // Check for active jobs
                const activeJob = response.data.find(job => 
                    job.status === 'pending' || job.status === 'processing'
                );
                
                if (activeJob) {
                    currentImportJob = activeJob.id;
                    startProgressTracking(activeJob.id);
                }
            }
        },
        error: function() {
            console.log('Failed to load import history');
        }
    });
}

function updateImportHistoryTable(jobs) {
    const tbody = $('#importJobsTable tbody');
    tbody.empty();
    
    jobs.forEach(function(job) {
        const statusBadge = getStatusBadge(job.status);
        const progressBar = job.status === 'processing' ? 
            `<div class="progress" style="height: 20px;">
                <div class="progress-bar" style="width: ${job.progress_percentage}%"></div>
            </div>` : 
            `${job.progress_percentage}%`;
            
        const row = `
            <tr>
                <td>${job.original_filename}</td>
                <td>${statusBadge}</td>
                <td>${progressBar}</td>
                <td>${job.processed_products} / ${job.total_products}</td>
                <td class="text-success">${job.successful_imports}</td>
                <td class="text-danger">${job.failed_imports}</td>
                <td>${new Date(job.created_at).toLocaleString()}</td>
                <td>
                    <button class="btn btn-sm btn-info" onclick="viewJobDetails(${job.id})">
                        <i class="fas fa-eye"></i>
                    </button>
                </td>
            </tr>
        `;
        tbody.append(row);
    });
}

function getStatusBadge(status) {
    const badges = {
        'pending': '<span class="badge badge-warning">Pending</span>',
        'processing': '<span class="badge badge-info">Processing</span>',
        'completed': '<span class="badge badge-success">Completed</span>',
        'failed': '<span class="badge badge-danger">Failed</span>'
    };
    return badges[status] || '<span class="badge badge-secondary">Unknown</span>';
}

function startProgressTracking(jobId) {
    if (progressInterval) {
        clearInterval(progressInterval);
    }
    
    progressInterval = setInterval(function() {
        $.ajax({
            url: `{{ url('admin/product/import/status') }}/${jobId}`,
            method: 'GET',
            success: function(response) {
                updateProgressDisplay(response);
                
                if (response.status === 'completed' || response.status === 'failed') {
                    clearInterval(progressInterval);
                    currentImportJob = null;
                    loadImportHistory(); // Refresh history
                }
            },
            error: function() {
                console.log('Failed to get job status');
            }
        });
    }, 2000); // Check every 2 seconds
}

function updateProgressDisplay(data) {
    $('#progressBar').css('width', data.progress + '%');
    $('#progressText').text(data.progress + '%');
    $('#processedText').text(data.processed);
    $('#totalText').text(data.total);
    $('#successfulText').text(data.successful);
    $('#failedText').text(data.failed);
    
    if (data.status === 'completed') {
        $('#progressSection .alert').removeClass('alert-warning').addClass('alert-success');
        $('#progressSection h5').html('<i class="fas fa-check-circle"></i> Import Completed!');
    } else if (data.status === 'failed') {
        $('#progressSection .alert').removeClass('alert-warning').addClass('alert-danger');
        $('#progressSection h5').html('<i class="fas fa-times-circle"></i> Import Failed!');
    }
}

function viewJobDetails(jobId) {
    // You can implement a modal or redirect to detailed view
    window.open(`{{ url('admin/product/import/job') }}/${jobId}`, '_blank');
}
</script>
@endsection

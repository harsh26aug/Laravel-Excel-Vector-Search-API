<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Category Search</title>
    
    <!-- Include Bootstrap 4 for styling -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <!-- Include DataTable CSS -->
    <link rel="stylesheet" href="https://cdn.datatables.net/1.11.5/css/jquery.dataTables.min.css">
    <style>
        .container {
            margin-top: 50px;
        }
        .table {
            margin-top: 20px;
        }
        .no-results {
            text-align: center;
            font-size: 18px;
            font-weight: bold;
        }
        .card {
            box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .card-header {
            background-color: #f8f9fa;
        }
        .search-input {
            border-radius: 25px;
            padding: 10px;
            width: 100%;
            max-width: 400px;
            margin: 0;
        }
        .search-button {
            border-radius: 25px;
        }
        .card-body {
            padding: 20px;
        }
        .search-box{
            margin-bottom: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="card">
        <div class="card-header">
            <h3 class="text-center">Category Search</h3>
        </div>
        <div class="card-body">
            <!-- Search Form -->
            <form method="GET" action="{{ route('category.search') }}" id="searchForm">
                <div class="input-group search-box">
                    <input type="text" id="searchInput" name="query" class="form-control search-input" value="{{ old('query', $query) }}" placeholder="Search categories...">
                    <div class="input-group-append">
                        <button type="submit" class="btn btn-primary search-button">Search</button>
                    </div>
                </div>
            </form>

            <!-- Results Table -->
            <div class="table-responsive">
                <table id="categoryTable" class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Name</th>
                            <th>Sub-category</th>
                            <th>Service</th>
                            <th>Match</th>
                        </tr>
                    </thead>
                    <tbody>
                        @if(empty($results))
                            <tr>
                                <td colspan="4" class="no-results">No results found.</td>
                            </tr>
                        @else
                            @foreach($results as $category)
                                <tr>
                                    <td>{{ $category->name }}</td>
                                    <td>{{ $category->sub_category }}</td>
                                    <td>{{ $category->service }}</td>
                                    <td>{{ $category->match }}</td>
                                </tr>
                            @endforeach
                        @endif
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Include jQuery and Bootstrap JS -->
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script src="https://cdn.datatables.net/1.11.5/js/jquery.dataTables.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.bundle.min.js"></script>

<script>
    $(document).ready(function() {
        // Initialize DataTable with sorting and pagination
        $('#categoryTable').DataTable({
            "paging": true,
            "searching": false, // Use the custom search form
            "info": false
        });
    });
</script>

</body>
</html>

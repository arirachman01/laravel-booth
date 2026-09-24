<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Upload File</title>

    <!-- Bootstrap CSS -->
    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body>

<div class="container mt-5">
    <div class="row justify-content-center">
        <div class="col-md-6">

            <h1 class="mb-4">Upload File</h1>

            <form method="POST" enctype="multipart/form-data">
                @csrf

                <!-- Upload File -->
                <div class="mb-3">
                    <label for="files" class="form-label">
                        Pilih File
                    </label>

                    <input
                        type="file"
                        id="files"
                        name="files[]"
                        multiple
                        class="form-control"
                    >

                    <div class="form-text">
                        Kamu dapat memilih beberapa file sekaligus.
                    </div>
                </div>

                <button type="submit" class="btn btn-primary">
                    Upload
                </button>

            </form>

        </div>
    </div>
</div>

<!-- Bootstrap JS -->
<script
    src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js">
</script>

</body>
</html>

<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Photo Results</title>

    <link
        href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet"
    >
</head>

<body class="bg-light">

    <div class="container py-5">

        {{-- Header --}}
        <div class="text-center mb-4">

            <h2 class="fw-bold">
                Photo Results
            </h2>

            <p class="text-muted">
                Session:
                <strong>{{ $session->session_id }}</strong>
            </p>

        </div>


        {{-- Results --}}
        <div class="row g-4">

            @forelse ($session->results as $result)

                <div class="col-12 col-sm-6 col-lg-4">

                    <div class="card shadow-sm h-100">

                        {{-- Image --}}
                        <img
                            src="{{ $result->url }}"
                            alt="{{ $result->filename }}"
                            class="card-img-top"
                            style="
                                height: 300px;
                                object-fit: contain;
                                background: #000;
                            "
                        >

                        <div class="card-body">

                            <h5 class="card-title text-truncate">
                                {{ $result->filename }}
                            </h5>

                            <p class="text-muted small">
                                Result ID: {{ $result->id }}
                            </p>

                            <a
                                href="{{ $result->url }}"
                                target="_blank"
                                class="btn btn-primary w-100"
                            >
                                Lihat Gambar
                            </a>

                        </div>

                    </div>

                </div>

            @empty

                <div class="col-12">

                    <div class="alert alert-warning text-center">
                        Tidak ada hasil untuk session ini.
                    </div>

                </div>

            @endforelse

        </div>

    </div>

</body>
</html>

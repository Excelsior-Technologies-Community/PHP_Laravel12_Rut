# PHP_Laravel12_Rut

## Introduction

PHP_Laravel12_Rut is a Laravel 12 web application designed to manage citizen records with secure RUT number handling and validation.

The project allows users to create, manage, update, delete, search, and display citizen information using a structured RUT management system.

This application demonstrates how to integrate external Laravel functionality into a real-world project while following clean architecture, database practices, and modern UI development standards.

The project focuses on:

* Citizen Management System
* RUT Validation and Processing
* RUT Formatting
* RUT Searching
* Random RUT Generation
* Database Integration
* Eloquent Model Usage
* CRUD Operations
* Form Validation
* Bootstrap 5 User Interface

This project serves as a practical example of building a Laravel-based management system with third-party library integration and clean development practices.

---

## Project Features

### Citizen Management

* Create Citizen
* View Citizen
* Update Citizen
* Delete Citizen

### RUT Features

* Validate RUT
* Parse RUT
* Format RUT
* Search by RUT
* Detect RUT Type
* Generate Random RUTs

### Database Features

* MySQL Integration
* Eloquent ORM
* Migration Support
* HasRut Trait

### User Interface

* Bootstrap 5
* Responsive Layout
* Navigation Menu
* Success Messages
* Validation Errors

---

## Package Used

Laragear Rut

Installation Command:

```bash
composer require laragear/rut
```

---

## Project Requirements

### Software Requirements

* PHP 8.2+
* Composer
* Laravel 12
* MySQL
* Bootstrap 5
* Laragear Rut Package

---

## Step 1 - Create Laravel Project

Open terminal and run:

```bash
composer create-project laravel/laravel PHP_Laravel12_Rut "12.*"
```

Move inside project:

```bash
cd PHP_Laravel12_Rut
```

---

## Step 2 - Configure Database

Create a new MySQL database.

```sql
CREATE DATABASE laravel12_rut;
```

Open:

```env
.env
```

Update database credentials:

```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laravel12_rut
DB_USERNAME=root
DB_PASSWORD=
```

Save the file.

---

## Step 3 - Install Laragear Rut Package

Run:

```bash
composer require laragear/rut
```

Publish configuration file:

```bash
php artisan vendor:publish --provider="Laragear\Rut\RutServiceProvider" --tag="config"
```

Publish translation files:

```bash
php artisan vendor:publish --provider="Laragear\Rut\RutServiceProvider" --tag="translations"
```

After publishing, you will get:

```text
config/rut.php
```

---

## Step 4 - Generate MVC Files

Create Model, Migration and Controller:

```bash
php artisan make:model Citizen -mcr
```

Generated Files:

```text
app/Models/Citizen.php

app/Http/Controllers/CitizenController.php

database/migrations/xxxx_xx_xx_xxxxxx_create_citizens_table.php
```

---

## Step 5 - Migration

Open:

```php
database/migrations/xxxx_xx_xx_xxxxxx_create_citizens_table.php
```

Replace with:

```php
<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('citizens', function (Blueprint $table) {

            $table->id();

            $table->string('name');

            $table->string('email')->unique();

            // Laragear Rut Blueprint Helper
            $table->rut();

            $table->timestamps();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('citizens');
    }
};
```

Run Migration:

```bash
php artisan migrate
```

Expected Database Structure:

```text
citizens
│
├── id
├── name
├── email
├── rut_num
├── rut_vd
├── created_at
└── updated_at
```

---

## Step 6 - Citizen Model

Open:

```php
app/Models/Citizen.php
```

Replace with:

```php
<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Laragear\Rut\HasRut;

class Citizen extends Model
{
    use HasRut;

    protected $fillable = [
        'name',
        'email',
        'rut_num',
        'rut_vd',
    ];
}
```

---

## Understanding HasRut Trait

The HasRut trait automatically provides:

```php
whereRut()

findRut()

findRutOrFail()

findManyRut()

whereRutIn()

whereRutLike()
```

Example:

```php
Citizen::whereRut('18.765.432-1')->first();
```

---

## Step 7 - Create Routes

Open:

```php
routes/web.php
```

Replace with:

```php
<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\CitizenController;

Route::get('/', function () {
    return redirect()->route('citizens.index');
});

Route::resource('citizens', CitizenController::class);

Route::get('/generator',
    [CitizenController::class, 'generator'])
    ->name('generator');

Route::post('/search-rut',
    [CitizenController::class, 'search'])
    ->name('search.rut');
```

---

## Step 8 - Citizen Controller

Open:

```php
app/Http/Controllers/CitizenController.php
```

Replace with:

```php
<?php

namespace App\Http\Controllers;

use App\Models\Citizen;
use Illuminate\Http\Request;
use Laragear\Rut\Rut;
use Laragear\Rut\Facades\Generator;

class CitizenController extends Controller
{
    /**
     * Display all citizens
     */
    public function index()
    {
        $citizens = Citizen::latest()->get();

        return view(
            'citizens.index',
            compact('citizens')
        );
    }

    /**
     * Show create form
     */
    public function create()
    {
        return view('citizens.create');
    }

    /**
     * Store citizen
     */
    public function store(Request $request)
    {
        $request->validate([
            'name'  => 'required|string|max:255',
            'email' => 'required|email|unique:citizens,email',
            'rut'   => 'required|rut',
        ]);

        $rut = Rut::parse($request->rut);

        Citizen::create([
            'name'    => $request->name,
            'email'   => $request->email,
            'rut_num' => $rut->num,
            'rut_vd'  => $rut->vd,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen created successfully.'
            );
    }

    /**
     * Show citizen details
     */
    public function show(Citizen $citizen)
    {
        return view(
            'citizens.show',
            compact('citizen')
        );
    }

    /**
     * Edit citizen
     */
    public function edit(Citizen $citizen)
    {
        return view(
            'citizens.edit',
            compact('citizen')
        );
    }

    /**
     * Update citizen
     */
    public function update(Request $request, Citizen $citizen)
    {
        $request->validate([
            'name' => 'required|string|max:255',

            'email' =>
            'required|email|unique:citizens,email,' .
                $citizen->id,

            'rut' => 'required|rut',
        ]);

        $rut = Rut::parse($request->rut);

        $citizen->update([
            'name'    => $request->name,
            'email'   => $request->email,
            'rut_num' => $rut->num,
            'rut_vd'  => $rut->vd,
        ]);

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen updated successfully.'
            );
    }

    /**
     * Delete citizen
     */
    public function destroy(Citizen $citizen)
    {
        $citizen->delete();

        return redirect()
            ->route('citizens.index')
            ->with(
                'success',
                'Citizen deleted successfully.'
            );
    }

    /**
     * Generate random RUTs
     */
    public function generator()
    {
        $ruts = Generator::asPeople()->make(20);

        return view(
            'citizens.generator',
            compact('ruts')
        );
    }

    /**
     * Search by RUT
     */
    public function search(Request $request)
    {
        $request->validate([
            'rut' => 'required|min:7'
        ]);

        $citizens = Citizen::whereRut(
            $request->rut
        )->get();


        return view(
            'citizens.index',
            compact('citizens')
        );
    }
}
```

---

## Step 9 - Create Layout File

Create:

```text
resources/views/layouts/app.blade.php
```

Add:

```blade
<!DOCTYPE html>
<html lang="en">

<head>

    <meta charset="UTF-8">

    <meta name="viewport"
        content="width=device-width, initial-scale=1">

    <title>PHP Laravel12 Rut</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css"
        rel="stylesheet">

    <style>
        body {
            background: #f8fafc;
        }

        .navbar-brand {
            font-weight: bold;
        }

        .card {
            border: none;
            box-shadow: 0 2px 10px rgba(0, 0, 0, .08);
        }

        .table th {
            vertical-align: middle;
        }
    </style>

</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-dark bg-primary">

        <div class="container">

            <a class="navbar-brand"
                href="{{ route('citizens.index') }}">

                PHP Laravel12 Rut

            </a>

            <button class="navbar-toggler"
                type="button"
                data-bs-toggle="collapse"
                data-bs-target="#navbarNav">

                <span class="navbar-toggler-icon"></span>

            </button>

            <div class="collapse navbar-collapse"
                id="navbarNav">

                <ul class="navbar-nav ms-auto">

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.index') }}">

                            Citizens

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('citizens.create') }}">

                            Add Citizen

                        </a>

                    </li>

                    <li class="nav-item">

                        <a class="nav-link"
                            href="{{ route('generator') }}">

                            Generate RUT

                        </a>

                    </li>

                </ul>

            </div>

        </div>

    </nav>

    <div class="container py-4">

        @if(session('success'))

        <div class="alert alert-success">

            {{ session('success') }}

        </div>

        @endif

        @yield('content')

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>

</body>

</html>
```

---

## Step 10 - Index Blade

Create:

```text
resources/views/citizens/index.blade.php
```

Add:

```blade
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Citizens Management</h2>

    <div>

        <a href="{{ route('generator') }}"
            class="btn btn-success">

            Generate RUTs

        </a>

        <a href="{{ route('citizens.create') }}"
            class="btn btn-primary">

            Add Citizen

        </a>

    </div>

</div>

<div class="card mb-4">

    <div class="card-body">

        <form action="{{ route('search.rut') }}"
            method="POST">

            @csrf

            <div class="row">

                <div class="col-md-10">

                    <input type="text"
                        name="rut"
                        class="form-control"
                        placeholder="Search By RUT"
                        value="{{ old('rut') }}"
                        required>

                </div>

                <div class="col-md-2">

                    <button class="btn btn-primary w-100">

                        Search

                    </button>

                </div>

            </div>

        </form>

    </div>

</div>

<table class="table table-bordered table-striped">

    <thead class="table-dark">

        <tr>

            <th>ID</th>
            <th>Name</th>
            <th>Email</th>
            <th>RUT</th>
            <th width="220">Action</th>

        </tr>

    </thead>

    <tbody>

        @forelse($citizens as $citizen)

        <tr>

            <td>{{ $citizen->id }}</td>

            <td>{{ $citizen->name }}</td>

            <td>{{ $citizen->email }}</td>

            <td>{{ $citizen->rut }}</td>

            <td>

                <a href="{{ route('citizens.show',$citizen->id) }}"
                    class="btn btn-info btn-sm">

                    View

                </a>

                <a href="{{ route('citizens.edit',$citizen->id) }}"
                    class="btn btn-warning btn-sm">

                    Edit

                </a>

                <form action="{{ route('citizens.destroy',$citizen->id) }}"
                    method="POST"
                    class="d-inline">

                    @csrf
                    @method('DELETE')

                    <button class="btn btn-danger btn-sm"
                        onclick="return confirm('Delete this record?')">

                        Delete

                    </button>

                </form>

            </td>

        </tr>

        @empty

        <tr>

            <td colspan="5"
                class="text-center">

                No Records Found

            </td>

        </tr>

        @endforelse

    </tbody>

</table>

@endsection
```

---

## Step 11 - Create Citizen Blade

Create:

```text
resources/views/citizens/create.blade.php
```

Add:

```blade
@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Create Citizen</h3>

    </div>

    <div class="card-body">

        @if($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach($errors->all() as $error)

                <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif

        <form action="{{ route('citizens.store') }}"
            method="POST">

            @csrf

            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                    name="name"
                    class="form-control"
                    value="{{ old('name') }}">

            </div>

            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                    name="email"
                    class="form-control"
                    value="{{ old('email') }}">

            </div>

            <div class="mb-3">

                <label>RUT</label>

                <input type="text"
                    name="rut"
                    class="form-control"
                    placeholder="Enter valid RUT"
                    value="{{ old('rut') }}">

                <small class="text-muted">
                    Example: Use a valid RUT generated from the Generator page.
                </small>

            </div>

            <button class="btn btn-success">

                Save Citizen

            </button>

            <a href="{{ route('citizens.index') }}"
                class="btn btn-secondary">

                Back

            </a>

        </form>

    </div>

</div>

@endsection
```

---

## Step 12 - Edit Citizen Blade

Create:

```text
resources/views/citizens/edit.blade.php
```

Add:

```blade
@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header">

        <h3>Edit Citizen</h3>

    </div>


    <div class="card-body">


        @if($errors->any())

        <div class="alert alert-danger">

            <ul>

                @foreach($errors->all() as $error)

                    <li>{{ $error }}</li>

                @endforeach

            </ul>

        </div>

        @endif



        <form action="{{ route('citizens.update',$citizen->id) }}"
              method="POST">

            @csrf
            @method('PUT')


            <div class="mb-3">

                <label>Name</label>

                <input type="text"
                       name="name"
                       class="form-control"
                       value="{{ old('name',$citizen->name) }}">

            </div>



            <div class="mb-3">

                <label>Email</label>

                <input type="email"
                       name="email"
                       class="form-control"
                       value="{{ old('email',$citizen->email) }}">

            </div>



            <div class="mb-3">

                <label>RUT</label>

                <input type="text"
                       name="rut"
                       class="form-control"
                       value="{{ old('rut',$citizen->rut) }}">


                <small class="text-muted">
                    Enter a valid generated RUT
                </small>

            </div>



            <button class="btn btn-primary">

                Update Citizen

            </button>


            <a href="{{ route('citizens.index') }}"
               class="btn btn-secondary">

                Back

            </a>


        </form>


    </div>

</div>


@endsection
```

---

## Step 13 - Show Citizen Blade

Create:

```text
resources/views/citizens/show.blade.php
```

Add:

```blade
@extends('layouts.app')

@section('content')

<div class="card">

    <div class="card-header bg-primary text-white">

        <h4 class="mb-0">
            Citizen Details
        </h4>

    </div>

    <div class="card-body">

        <table class="table table-bordered">

            <tr>
                <th width="250">Name</th>
                <td>{{ $citizen->name }}</td>
            </tr>

            <tr>
                <th>Email</th>
                <td>{{ $citizen->email }}</td>
            </tr>

            <tr>
                <th>Formatted RUT</th>
                <td>{{ $citizen->rut }}</td>
            </tr>

            <tr>
                <th>RUT Number</th>
                <td>{{ $citizen->rut_num }}</td>
            </tr>

            <tr>
                <th>Verification Digit</th>
                <td>{{ $citizen->rut_vd }}</td>
            </tr>

        </table>

        <a href="{{ route('citizens.index') }}"
            class="btn btn-secondary">

            Back

        </a>

    </div>

</div>

@endsection
```

---

## Step 14 - Generator Blade

Create:

```text
resources/views/citizens/generator.blade.php
```

Add:

```blade
@extends('layouts.app')

@section('content')

<div class="d-flex justify-content-between align-items-center mb-4">

    <h2>Generated RUTs</h2>

    <a href="{{ route('citizens.index') }}"
        class="btn btn-secondary">

        Back

    </a>

</div>

<div class="card">

    <div class="card-body">

        <table class="table table-bordered">

            <thead class="table-dark">

                <tr>

                    <th>#</th>
                    <th>Generated RUT</th>
                    <th>Type</th>

                </tr>

            </thead>

            <tbody>

                @foreach($ruts as $index => $rut)

                <tr>

                    <td>{{ $index + 1 }}</td>

                    <td>{{ $rut }}</td>

                    <td>

                        @if($rut->isPerson())
                        Person
                        @elseif($rut->isCompany())
                        Company
                        @elseif($rut->isTemporal())
                        Temporal
                        @elseif($rut->isInvestor())
                        Investor
                        @else
                        Other
                        @endif

                    </td>

                </tr>

                @endforeach

            </tbody>

        </table>

    </div>

</div>

@endsection
```

---

## Step 15 - Testing the Application

### Create Citizen

Navigate to:

```text
http://127.0.0.1:8000/citizens/create
```

Enter:

```text
Name  : John Doe
Email : john@example.com
RUT   : Use a valid RUT generated from the Generator page
```

Click:

```text
Save Citizen
```

Expected:

```text
Citizen created successfully.
```

---

### Search Citizen By RUT

Search:

Use a valid RUT stored in the database

Expected:

Matching citizen record displayed in the Citizens Management table

---

### Generate Random RUTs

Click:

```text
Generate RUTs
```

Expected:

```text
20 Random Valid RUTs
```

---

### Edit Citizen

Click:

```text
Edit
```

Update:

```text
Name
Email
RUT
```

Expected:

```text
Citizen updated successfully.
```

---

### Delete Citizen

Click:

```text
Delete
```

Expected:

```text
Citizen deleted successfully.
```

---

## Step 16 - Run Project

Start Laravel Server:

```bash
php artisan serve
```

Open Browser:

```text
http://127.0.0.1:8000
```

---

## Screenshots

### Generated RUTs

<img width="1901" height="1028" alt="Screenshot 2026-06-22 144143" src="https://github.com/user-attachments/assets/b3f87b07-5874-48b4-85e3-c03f5607322f" />


### Index Page

<img width="1918" height="1032" alt="Screenshot 2026-06-22 162821" src="https://github.com/user-attachments/assets/e7cef4df-9958-4dcc-aa01-cb7a2fdc1754" />


### Create Citizen

<img width="1918" height="1027" alt="Screenshot 2026-06-22 144216" src="https://github.com/user-attachments/assets/780553b0-4b66-4eb7-82b4-38bfb2821008" />

<img width="1918" height="1027" alt="Screenshot 2026-06-22 144227" src="https://github.com/user-attachments/assets/69349a45-4d37-4182-97f2-d2601ce4f225" />


### Citizen List

<img width="1918" height="1027" alt="Screenshot 2026-06-22 144238" src="https://github.com/user-attachments/assets/2ad7639f-b204-4fed-a47d-68fd6f73bf14" />


### Edit Page

<img width="1917" height="1021" alt="Screenshot 2026-06-22 145154" src="https://github.com/user-attachments/assets/83838921-9aef-47a3-9238-75834710c708" />

<img width="1918" height="1022" alt="Screenshot 2026-06-22 145201" src="https://github.com/user-attachments/assets/6598c7b3-6d55-44fb-b79b-f0c955912a97" />


---

## Project Structure

```text
PHP_Laravel12_Rut
│
├── app
│   ├── Http
│   │   └── Controllers
│   │       └── CitizenController.php
│   │
│   └── Models
│       └── Citizen.php
│
├── config
│   └── rut.php
│
├── database
│   └── migrations
│       └── create_citizens_table.php
│
├── resources
│   └── views
│       ├── layouts
│       │   └── app.blade.php
│       │
│       └── citizens
│           ├── index.blade.php
│           ├── create.blade.php
│           ├── edit.blade.php
│           ├── show.blade.php
│           └── generator.blade.php
│
├── routes
│   └── web.php
│
├── .env
│
└── README.md
```

---

## Conclusion

PHP_Laravel12_Rut is a Laravel 12 application that demonstrates secure citizen management with RUT validation, generation, searching, and CRUD operations using modern Laravel development practices.


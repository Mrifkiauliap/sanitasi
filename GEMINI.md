# GEMINI Project Guidelines

This file documents the specific coding standards and architectural decisions for the `monitoring-wilayah-bnn` project.

## Authentication
- **User Identity**: The system uses `username` (not `email`) for authentication.
- **Registration**: Public registration is disabled. Users are managed by administrators/seeders.
- **Unused Features**: `Forgot Password` and `Verify Email` features have been removed.

## Controllers and API Responses
All controllers must extend the base `App\Http\Controllers\Controller` class to inherit the standard Laravel functionality and our custom helper methods.

### JSON Response Helpers
When returning JSON responses (e.g., for API endpoints or AJAX requests), use the following helper methods defined in the base controller:

#### Success Response
Return a successful response with data.
```php
// Signature
protected function success($data = null, $message = 'Success', $code = 200)

// Usage
return $this->success($user, 'User data retrieved successfully');
```

#### Error Response
Return an error response.
```php
// Signature
protected function error($message, $code = 400)

// Usage
return $this->error('User not found', 404);
```

### Standard Response Structure
The helper methods ensure a consistent JSON structure:
```json
{
    "success": true|false,
    "message": "String message",
    "data": { ... } // Optional, only for success
}
```

## Frontend
- **Blade Components**: Use Laravel Breeze components (e.g., `x-input-label`, `x-text-input`, `x-primary-button`) for consistency.
- **Tailwind CSS**: Use Tailwind utility classes for styling.

## Language
- **Bahasa Indonesia**: Use Indonesian for all user-facing text, error messages, and variable/function names where appropriate. This ensures consistency and understanding for the target audience.

## Component Patterns

### 1. Data Table (`<x-data-table>`)

Server-side table. Controller must return specific JSON structure.

**Controller Logic:**

**Fitur:**

- Pencarian data (debounced)
- Sorting per kolom
- Paginasi
- Pemilihan jumlah data per halaman
- Loading & Empty state

**Contoh Penggunaan:**

**1. Controller (`PegawaiController.php`)**
Pastikan method `index` bisa merespon request JSON.

```php
public function index(Request $request)
{
    if ($request->wantsJson()) {
        $query = Models::query();
        // ... logika search, sort, paginate ...
        return response()->json([
            'data' => $pegawais->items(),
            'total' => $total,
        ]);
    }
    return view('route.index');
}
```

**2. View (`route/index.blade.php`)**

```html
<x-data-table url="{{ route('route.index') }}">
    <x-slot name="header"> // Tombol atau elemen lain di atas tabel </x-slot>

    <x-slot name="thead">
        <th @click="sortBy('nama_lengkap')">Nama</th>
        // ... header lainnya
    </x-slot>

    <x-slot name="tbody">
        <tr>
            <td x-text="item.nama_lengkap"></td>
            // ... body lainnya
        </tr>
    </x-slot>
</x-data-table>
```

### 2. Forms (`<x-forms.*>`)

Location: `resources/views/components/forms`. Handles titles, errors, and notes automatically.
**Pattern:** Use `old('field', $model->field)` for state retention.

**Inputs:**

- `text`, `textarea`, `date`, `checkbox`
- **Select:** `:options="['id' => 'Label']" :selected="old(...)"`

```html
<x-forms.text name="nama" :value="old('nama', $item->nama)" required />
```

### 3. Feedback Components

**Toast (`<x-toast-notification>`)**

- **Props:**
    - `theme`: `'light'` | `'dark'`
    - `default-position`: `'top-right'` | `'top-left'` | `'top-center'` | `'bottom-right'` | `'bottom-left'` | `'bottom-center'`
    - `max-toasts`: `integer` (default: 5)
- **Backend:** `->with('notification', ['type'=>'success', 'title'=>'Title', 'message'=>'Msg'])`
- **Frontend:**
    ```js
    window.dispatchEvent(new CustomEvent('open-toast', {
        detail: {
            type: 'success',        // success | error | danger | warning | info
            title: 'Berhasil!',
            message: 'Data disimpan.',
            position: 'top-right',  // opsional — override default-position
            duration: 5000,         // ms, default 5000
            autoClose: true,        // default true
            dismissible: true,      // default true
        }
    }));
    ```

**Confirm Modal (`<x-modal-confirm>`)**

- **Props:**
    - `theme`: `'light'` | `'dark'`
- **Trigger via JS/Alpine:**
    ```js
    $dispatch('open-confirm-modal', {
        title: 'Hapus Data?',
        message: 'Aksi ini <strong>tidak bisa dibatalkan</strong>.',
        action: '/items/1',
        method: 'DELETE',           // POST | PUT | PATCH | DELETE
        type: 'danger',             // danger | success | info | warning
        confirmText: 'Ya, Hapus',   // opsional
        cancelText: 'Batal',        // opsional
        size: 'md',                 // sm | md | lg
    })
    ```

### 4. Layout Components

**`<x-card>`**
Navigation card. Props: `icon`, `title`, `description`, `href`.

**`<x-content-card>`**
Main container for tables/forms.
Slots: `action` (header right), `footer` (bottom actions).

**[CRITICAL] Form Rule:**
When using `<x-content-card>` for forms, the `<form>` tag **MUST wrap** the component, not be inside it.
✅ **Correct:**

```html
<form method="POST">
    @csrf
    <x-content-card title="Edit Data">
        <!-- Inputs -->
        <x-slot name="footer"><button>Save</button></x-slot>
    </x-content-card>
</form>
```

❌ **Incorrect:** `<x-content-card><form>...</form></x-content-card>` (Buttons in footer won't submit).

# Views Documentation

This document describes the view structure and components in the Angaza Referral System.

## View Structure

### Layouts
- `layouts/app.blade.php` - Main application layout
- `layouts/auth.blade.php` - Authentication pages layout
- `layouts/admin.blade.php` - Admin dashboard layout

### Components
- `components/forms/` - Reusable form components
- `components/tables/` - Data table components
- `components/cards/` - Card-based UI components
- `components/modals/` - Modal dialog components

## Blade Templates

### Main Layout
```php
<!DOCTYPE html>
<html>
<head>
    <title>@yield('title') - Angaza Referral</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    @vite(['resources/css/app.css', 'resources/js/app.js'])
</head>
<body>
    <nav>@include('components.navigation')</nav>
    <main>@yield('content')</main>
    <footer>@include('components.footer')</footer>
</body>
</html>
```

### Form Components
```php
<div class="form-group">
    <label for="{{ $name }}">{{ $label }}</label>
    <input type="{{ $type }}" 
           name="{{ $name }}" 
           id="{{ $name }}" 
           class="form-control @error($name) is-invalid @enderror"
           value="{{ old($name) }}">
    @error($name)
        <div class="invalid-feedback">{{ $message }}</div>
    @enderror
</div>
```

## JavaScript Components

### Vue Components
```vue
<template>
  <div class="referral-form">
    <form @submit.prevent="submit">
      <patient-select v-model="form.patient_id" />
      <facility-select v-model="form.facility_id" />
      <button type="submit">Create Referral</button>
    </form>
  </div>
</template>

<script>
export default {
  data() {
    return {
      form: {
        patient_id: null,
        facility_id: null
      }
    }
  },
  methods: {
    async submit() {
      try {
        await axios.post('/api/referrals', this.form)
        this.$router.push('/referrals')
      } catch (error) {
        console.error(error)
      }
    }
  }
}
</script>
```

## Styling

### CSS Structure
- `resources/css/app.css` - Main stylesheet
- `resources/css/components/` - Component-specific styles
- `resources/css/pages/` - Page-specific styles

### Tailwind Classes
```html
<div class="container mx-auto px-4">
  <div class="bg-white rounded-lg shadow-md p-6">
    <h2 class="text-2xl font-bold mb-4">Referral Details</h2>
    <div class="grid grid-cols-2 gap-4">
      <!-- Content -->
    </div>
  </div>
</div>
```

## Best Practices

1. Use components for reusable UI elements
2. Follow BEM naming convention
3. Keep views focused and simple
4. Use layouts for consistent structure
5. Implement responsive design 
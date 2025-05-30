<template>
  <div class="container mx-auto p-6 max-w-2xl">
    <div class="mb-6">
      <Link 
        href="/listings" 
        class="text-blue-500 hover:text-blue-700 flex items-center"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
        </svg>
        Volver al listado
      </Link>
      <h1 class="text-2xl font-bold mt-2">Crear Nueva Oferta</h1>
    </div>

    <form @submit.prevent="submitForm" class="space-y-6">
      <div class="space-y-6">
        <!-- Metal Selection -->
        <div>
          <label for="metal_id" class="block text-sm font-medium text-gray-700">Tipo de Metal</label>
          <select
            id="metal_id"
            v-model="form.metal_id"
            @blur="validateField('metal_id')"
            :class="[
              'mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
              errors.metal_id ? 'border-red-300 text-red-900 placeholder-red-300' : 'border-gray-300'
            ]"
          >
            <option value="" disabled>Selecciona un metal</option>
            <option v-for="metal in metals" :key="metal.id" :value="metal.id">
              {{ metal.name }}
            </option>
          </select>
          <p v-if="errors.metal_id" class="mt-1 text-sm text-red-600">
            {{ errors.metal_id }}
          </p>
        </div>

        <!-- Quality -->
        <div>
          <label for="quality" class="block text-sm font-medium text-gray-700">Calidad</label>
          <input
            type="text"
            id="quality"
            v-model="form.quality"
            @blur="validateField('quality')"
            placeholder="Ej: Primera, Segunda, etc."
            :class="[
              'mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
              errors.quality ? 'border-red-300 text-red-900 placeholder-red-300' : 'border-gray-300'
            ]"
          />
          <p v-if="errors.quality" class="mt-1 text-sm text-red-600">
            {{ errors.quality }}
          </p>
        </div>

        <!-- Price -->
        <div>
          <label for="price" class="block text-sm font-medium text-gray-700">Precio por tonelada (MXN)</label>
          <div class="mt-1 relative rounded-md shadow-sm">
            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
              <span class="text-gray-500 sm:text-sm">$</span>
            </div>
            <input
              type="number"
              id="price"
              v-model.number="form.price"
              @blur="validateField('price')"
              min="0"
              step="0.01"
              :class="[
                'pl-7 block w-full rounded-md focus:ring-blue-500 focus:border-blue-500',
                errors.price ? 'border-red-300 text-red-900 placeholder-red-300' : 'border-gray-300'
              ]"
            />
          </div>
          <p v-if="errors.price" class="mt-1 text-sm text-red-600">
            {{ errors.price }}
          </p>
        </div>

        <!-- Quantity -->
        <div>
          <label for="quantity" class="block text-sm font-medium text-gray-700">Cantidad (toneladas)</label>
          <input
            type="number"
            id="quantity"
            v-model.number="form.quantity"
            @blur="validateField('quantity')"
            min="1"
            :class="[
              'mt-1 block w-full rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500',
              errors.quantity ? 'border-red-300 text-red-900 placeholder-red-300' : 'border-gray-300'
            ]"
          />
          <p v-if="errors.quantity" class="mt-1 text-sm text-red-600">
            {{ errors.quantity }}
          </p>
        </div>
      </div>

      <div class="flex justify-end space-x-4 pt-4">
        <Link 
          href="/listings" 
          class="px-4 py-2 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          Cancelar
        </Link>
        <button
          type="submit"
          :disabled="saving"
          class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 disabled:opacity-50"
        >
          <span v-if="saving">
            <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline-block" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
              <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
              <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Guardando...
          </span>
          <span v-else>Guardar Oferta</span>
        </button>
      </div>
    </form>
  </div>
</template>

<script setup>
import { reactive, ref, getCurrentInstance } from 'vue';
import { Link, router } from '@inertiajs/vue3';

const { proxy } = getCurrentInstance();

const props = defineProps({
  metals: {
    type: Array,
    required: true,
    default: () => []
  },
  flash: {
    type: Object,
    default: () => ({})
  }
});

const saving = ref(false);
const errors = reactive({
  metal_id: '',
  quality: '',
  price: '',
  quantity: ''
});

const form = reactive({
  metal_id: '',
  quality: '',
  price: null,
  quantity: 1,
});

// Mostrar mensaje flash si existe
if (props.flash.success) {
  proxy.$toast.success(props.flash.success);
} else if (props.flash.error) {
  proxy.$toast.error(props.flash.error);
}

const showToast = (message, type = 'success') => {
  if (type === 'success') {
    proxy.$toast.success(message);
  } else {
    proxy.$toast.error(message);
  }
};

const validateField = (field) => {
  const value = form[field];
  
  // Reset error
  errors[field] = '';
  
  // Validate required fields
  if (field === 'metal_id' && !value) {
    errors[field] = 'Por favor selecciona un tipo de metal';
    return false;
  }
  
  if (field === 'quality' && !value.trim()) {
    errors[field] = 'La calidad es requerida';
    return false;
  }
  
  if (field === 'price') {
    if (value === null || value === '') {
      errors[field] = 'El precio es requerido';
      return false;
    }
    if (isNaN(value) || value <= 0) {
      errors[field] = 'El precio debe ser mayor a 0';
      return false;
    }
  }
  
  if (field === 'quantity') {
    if (!value) {
      errors[field] = 'La cantidad es requerida';
      return false;
    }
    if (isNaN(value) || value < 1) {
      errors[field] = 'La cantidad debe ser al menos 1 tonelada';
      return false;
    }
  }
  
  return true;
};

const validateForm = () => {
  let isValid = true;
  
  // Validate all fields
  Object.keys(form).forEach(field => {
    if (!validateField(field)) {
      isValid = false;
    }
  });
  
  return isValid;
};

const submitForm = () => {
  if (!validateForm()) {
    showToast('Por favor corrige los errores en el formulario', 'error');
    return;
  }
  
  saving.value = true;
  
  router.post('/listings', form, {
    preserveScroll: true,
    onSuccess: () => {
      showToast('Oferta creada exitosamente');
      // Reset form
      form.metal_id = '';
      form.quality = '';
      form.price = null;
      form.quantity = 1;
      // Redirect after a short delay
      setTimeout(() => {
        router.visit('/listings');
      }, 1000);
    },
    onError: (err) => {
      if (err.errors) {
        // Handle server-side validation errors
        Object.keys(err.errors).forEach(field => {
          if (errors.hasOwnProperty(field)) {
            errors[field] = err.errors[field][0];
          }
        });
        
        if (err.message) {
          showToast(err.message, 'error');
        } else {
          showToast('Error al guardar la oferta. Por favor, verifica los datos.', 'error');
        }
      }
    },
    onFinish: () => {
      saving.value = false;
    }
  });
};
</script>

<style>
/* Estilos personalizados para los toasts */
.Vue-Toastification__toast--success {
  background-color: #10B981 !important;
}

.Vue-Toastification__toast--error {
  background-color: #EF4444 !important;
}

.Vue-Toastification__toast-body {
  font-family: 'Inter', sans-serif;
}

.Vue-Toastification__close-button {
  color: white !important;
  opacity: 0.8;
}

.Vue-Toastification__close-button:hover {
  opacity: 1;
}
</style>

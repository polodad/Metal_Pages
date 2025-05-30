<template>
  <div class="container mx-auto p-6">
    <div class="flex justify-between items-center mb-6">
      <h1 class="text-2xl font-bold">Ofertas de Metales</h1>
      <Link 
        href="/listings/create" 
        class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-md transition flex items-center"
      >
        <svg class="w-4 h-4 mr-1" fill="none" stroke="currentColor" viewBox="0 0 24 24">
          <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4" />
        </svg>
        Crear Oferta
      </Link>
    </div>

    <!-- Mensajes flash -->
    <div v-if="flash.success" class="mb-4 p-4 bg-green-100 text-green-700 rounded">
      {{ flash.success }}
    </div>
    <div v-else-if="flash.error" class="mb-4 p-4 bg-red-100 text-red-700 rounded">
      {{ flash.error }}
    </div>

    <div v-if="listings.length === 0" class="text-center py-12 bg-gray-50 rounded-lg">
      <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1" d="M20 7l-8-4.5L4 7m16 0l-8 4.5M4 7v10l8 4.5M12 11.5V22m0 0l8-4.5M4 7l8 4.5m0 0L20 7" />
      </svg>
      <h3 class="mt-2 text-lg font-medium text-gray-900">No hay ofertas disponibles</h3>
      <p class="mt-1 text-gray-500">Empieza creando una nueva oferta.</p>
      <div class="mt-6">
        <Link 
          href="/listings/create" 
          class="inline-flex items-center px-4 py-2 border border-transparent rounded-md shadow-sm text-sm font-medium text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500"
        >
          <svg class="-ml-1 mr-2 h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6" />
          </svg>
          Nueva oferta
        </Link>
      </div>
    </div>

    <div v-if="loading" class="flex justify-center py-8">
      <div class="animate-spin rounded-full h-12 w-12 border-t-2 border-b-2 border-blue-500"></div>
    </div>
    
    <ul v-else class="space-y-4">
      <li 
        v-for="listing in listings" 
        :key="listing.id"
        class="border rounded-lg p-4 hover:shadow-md transition bg-white"
      >
        <div class="flex flex-col md:flex-row md:justify-between md:items-center">
          <div class="flex-1">
            <div class="flex items-center">
              <h3 class="text-lg font-semibold text-gray-900">
                {{ listing.metal?.name || 'Metal no especificado' }}
              </h3>
              <span class="ml-2 px-2 py-0.5 text-xs font-medium bg-blue-100 text-blue-800 rounded-full">
                {{ listing.quality }}
              </span>
            </div>
            
            <div class="mt-2 flex flex-wrap gap-4 text-sm text-gray-600">
              <div class="flex items-center">
                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.308-.204-3.2-.6M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
                <span class="font-medium">${{ Number(listing.price).toLocaleString() }}</span> / tonelada
              </div>
              <div class="flex items-center">
                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 6l3 1m0 0l-3 9a5.002 5.002 0 006.001 0M6 7l3 9M6 7l6-2m6 2l3-1m-3 1l-3 9a5.002 5.002 0 006.001 0M18 7l3 9m-3-9l-6-2m0 0V5m0 0a2 2 0 110-4h4a2 2 0 110 4h-4z" />
                </svg>
                <span class="font-medium">{{ listing.quantity }} toneladas</span>
              </div>
              <div class="flex items-center">
                <svg class="flex-shrink-0 mr-1.5 h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                </svg>
                <span>Publicado por {{ listing.user?.name || 'Anónimo' }}</span>
              </div>
            </div>
          </div>
          
          <div class="mt-4 md:mt-0 md:ml-4 flex items-center">
            <div class="text-right">
              <p class="text-2xl font-bold text-blue-600">
                ${{ (listing.price * listing.quantity).toLocaleString() }}
              </p>
              <p class="text-sm text-gray-500">Total</p>
            </div>
            
            <div class="ml-4 flex-shrink-0 flex">
              <Link 
                :href="`/listings/${listing.id}/edit`" 
                class="mr-2 p-2 rounded-full text-gray-400 hover:bg-gray-100 hover:text-gray-500"
                title="Editar"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z" />
                </svg>
              </Link>
              <button 
                @click="confirmDelete(listing)" 
                class="p-2 rounded-full text-red-400 hover:bg-red-50 hover:text-red-600"
                title="Eliminar"
              >
                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                  <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                </svg>
              </button>
            </div>
          </div>
        </div>
      </li>
    </ul>
    
    <!-- Paginación -->
    <div v-if="meta.total > 0" class="mt-8 flex flex-col sm:flex-row items-center justify-between px-4 py-3 bg-white border-t border-gray-200 sm:px-6 rounded-lg">
      <div class="hidden sm:block">
        <p class="text-sm text-gray-700">
          Mostrando
          <span class="font-medium">{{ (meta.current_page - 1) * meta.per_page + 1 }}</span>
          a
          <span class="font-medium">{{ Math.min(meta.current_page * meta.per_page, meta.total) }}</span>
          de
          <span class="font-medium">{{ meta.total }}</span>
          resultados
        </p>
      </div>
      <div class="flex-1 flex justify-between sm:justify-end space-x-2">
        <button
          @click="changePage(meta.current_page - 1)"
          :disabled="!links.prev || loading"
          :class="[
            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
            links.prev && !loading 
              ? 'bg-white text-gray-700 hover:bg-gray-50' 
              : 'bg-gray-100 text-gray-400 cursor-not-allowed'
          ]"
        >
          Anterior
        </button>
        
        <div class="hidden md:flex items-center space-x-1">
          <template v-for="page in meta.last_page" :key="page">
            <button
              v-if="Math.abs(page - meta.current_page) < 3 || page === 1 || page === meta.last_page"
              @click="changePage(page)"
              :class="[
                'px-4 py-2 rounded-md text-sm font-medium',
                page === meta.current_page 
                  ? 'bg-blue-600 text-white' 
                  : 'text-gray-700 hover:bg-gray-100'
              ]"
            >
              {{ page }}
            </button>
            <span 
              v-else-if="Math.abs(page - meta.current_page) === 3" 
              class="px-4 py-2 text-gray-500"
            >
              ...
            </span>
          </template>
        </div>
        
        <span class="md:hidden text-sm text-gray-700">
          Página {{ meta.current_page }} de {{ meta.last_page }}
        </span>
        
        <button
          @click="changePage(meta.current_page + 1)"
          :disabled="!links.next || loading"
          :class="[
            'relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md',
            links.next && !loading 
              ? 'bg-white text-gray-700 hover:bg-gray-50' 
              : 'bg-gray-100 text-gray-400 cursor-not-allowed'
          ]"
        >
          Siguiente
        </button>
      </div>
    </div>
    
    <!-- Modal de confirmación de eliminación -->
    <div v-if="showDeleteModal" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center z-50 p-4">
      <div class="bg-white rounded-lg max-w-md w-full p-6 shadow-xl">
        <div class="sm:flex sm:items-start">
          <div class="mx-auto flex-shrink-0 flex items-center justify-center h-12 w-12 rounded-full bg-red-100 sm:mx-0 sm:h-10 sm:w-10">
            <svg class="h-6 w-6 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
              <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
            </svg>
          </div>
          <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
              ¿Eliminar oferta?
            </h3>
            <div class="mt-2">
              <p class="text-sm text-gray-500">
                ¿Estás seguro de que quieres eliminar esta oferta? Esta acción no se puede deshacer.
              </p>
            </div>
          </div>
        </div>
        <div class="mt-5 sm:mt-4 sm:flex sm:flex-row-reverse">
          <button 
            @click="deleteListing" 
            type="button" 
            class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-red-600 text-base font-medium text-white hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500 sm:ml-3 sm:w-auto sm:text-sm"
            :disabled="deleting"
          >
            <span v-if="!deleting">Eliminar</span>
            <span v-else class="flex items-center">
              <svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
              </svg>
              Eliminando...
            </span>
          </button>
          <button 
            @click="showDeleteModal = false" 
            type="button" 
            class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500 sm:mt-0 sm:w-auto sm:text-sm"
            :disabled="deleting"
          >
            Cancelar
          </button>
        </div>
      </div>
    </div>
  </div>
</template>

<script setup>
import { ref, reactive, onMounted, watch } from 'vue';
import { Link, router } from '@inertiajs/vue3';
import axios from 'axios';

const { proxy } = getCurrentInstance();

const props = defineProps({
  listings: {
    type: Array,
    default: () => []
  },
  meta: {
    type: Object,
    default: () => ({
      current_page: 1,
      last_page: 1,
      per_page: 10,
      total: 0
    })
  },
  links: {
    type: Object,
    default: () => ({
      first: null,
      last: null,
      prev: null,
      next: null
    })
  },
  flash: {
    type: Object,
    default: () => ({})
  }
});

const listings = ref(props.listings);
const meta = reactive({ ...props.meta });
const links = reactive({ ...props.links });
const loading = ref(false);
const showDeleteModal = ref(false);
const deleting = ref(false);
const listingToDelete = ref(null);

// Cargar datos de la API
const fetchListings = async (page = 1) => {
  try {
    loading.value = true;
    const response = await axios.get(`/listings?page=${page}`);
    
    listings.value = response.data.data;
    Object.assign(meta, response.data.meta);
    Object.assign(links, response.data.links);
  } catch (error) {
    console.error('Error al cargar las ofertas:', error);
    proxy.$toast.error('Error al cargar las ofertas');
  } finally {
    loading.value = false;
  }
};

// Cambiar de página
const changePage = (page) => {
  if (page < 1 || page > meta.last_page) return;
  fetchListings(page);
  window.scrollTo({ top: 0, behavior: 'smooth' });
};

// Mostrar mensaje flash si existe
if (props.flash.success) {
  proxy.$toast.success(props.flash.success);
} else if (props.flash.error) {
  proxy.$toast.error(props.flash.error);
}

const confirmDelete = (listing) => {
  listingToDelete.value = listing;
  showDeleteModal.value = true;
};

const deleteListing = async () => {
  if (!listingToDelete.value) return;
  
  deleting.value = true;
  
  try {
    await axios.delete(`/listings/${listingToDelete.value.id}`);
    proxy.$toast.success('Oferta eliminada correctamente');
    showDeleteModal.value = false;
    
    // Recargar la lista después de eliminar
    // Si era el último elemento de la página y no es la primera página, ir a la página anterior
    if (listings.value.length === 1 && meta.current_page > 1) {
      fetchListings(meta.current_page - 1);
    } else {
      fetchListings(meta.current_page);
    }
  } catch (error) {
    console.error('Error al eliminar la oferta:', error);
    proxy.$toast.error('Error al eliminar la oferta');
  } finally {
    deleting.value = false;
    listingToDelete.value = null;
  }
};

// Cargar datos iniciales si no se pasaron por props
onMounted(() => {
  if (listings.value.length === 0) {
    fetchListings(1);
  }
});
</script>

<style scoped>
/* Estilos personalizados */
.fade-enter-active, .fade-leave-active {
  transition: opacity 0.3s ease;
}
.fade-enter-from, .fade-leave-to {
  opacity: 0;
}
</style>

<template>
  <div class="bg-gray-50 min-h-screen">
    <!-- Header -->
    <header class="bg-white border-b border-gray-200">
      <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
        <div class="flex justify-between items-center h-16">
          <div class="flex items-center space-x-8">
            <div class="text-xl font-bold text-gray-900">Need Supply Co.</div>
            <nav class="hidden md:flex space-x-8">
              <a href="#" class="text-gray-700 hover:text-gray-900">Mens</a>
              <a href="#" class="text-gray-700 hover:text-gray-900">Womens</a>
              <a href="#" class="text-gray-700 hover:text-gray-900">Blog</a>
            </nav>
          </div>
          <div class="flex items-center space-x-4">
            <span class="text-sm text-gray-600">Call (5)</span>
            <span class="text-sm text-gray-600">Currency</span>
            <span class="text-sm text-gray-600">Account</span>
            <span class="text-sm text-gray-600">Search</span>
          </div>
        </div>
      </div>
    </header>

    <!-- Breadcrumb -->
    <nav class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
      <div class="flex items-center space-x-2 text-sm text-gray-500">
        <a href="#" class="hover:text-gray-700">Home</a>
        <span>/</span>
        <a href="#" class="hover:text-gray-700">Mens</a>
        <span>/</span>
        <a href="#" class="hover:text-gray-700">Sweatshirts</a>
        <span>/</span>
        <span class="text-gray-900">Fleece Sweater</span>
      </div>
    </nav>

    <!-- Product Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
      <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        <!-- Product Images -->
        <div class="flex flex-col-reverse lg:flex-row gap-4">
          <!-- Thumbnail Images -->
          <div class="flex lg:flex-col gap-2 overflow-x-auto lg:overflow-visible">
            <div
                v-for="(image, index) in productImages"
                :key="index"
                class="flex-shrink-0 w-16 h-20 bg-gray-200 rounded cursor-pointer hover:opacity-75 transition-opacity"
                :class="{ 'ring-2 ring-blue-500': selectedImageIndex === index }"
                @click="selectedImageIndex = index"
            >
              <img
                  :src="image"
                  :alt="`Product view ${index + 1}`"
                  class="w-full h-full object-cover rounded"
              />
            </div>
          </div>

          <!-- Main Product Image -->
          <div class="flex-1 bg-gray-100 rounded-lg overflow-hidden">
            <img
                :src="productImages[selectedImageIndex]"
                alt="Fleece Sweater"
                class="w-full h-full object-cover"
            />
          </div>
        </div>

        <!-- Product Details -->
        <div class="flex flex-col">
          <div class="mb-6">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">FLEECE SWEATER</h1>
            <p class="text-gray-600 mb-4">Mens & Womens</p>
            <div class="flex items-center space-x-4 mb-4">
              <span class="text-sm text-gray-500">Size</span>
              <span class="text-sm text-gray-500">XS</span>
              <span class="text-sm text-gray-500">S</span>
              <span class="text-sm text-gray-500">M</span>
              <span class="text-sm text-gray-500">L</span>
              <span class="text-sm text-gray-500">XL</span>
            </div>
            <div class="text-3xl font-bold text-gray-900 mb-6">${{ product.price }}</div>
          </div>

          <!-- Size Selection -->
          <div class="mb-6">
            <label class="block text-sm font-medium text-gray-700 mb-2">Size</label>
            <select
                v-model="selectedSize"
                class="w-full border border-gray-300 rounded-md px-3 py-2 focus:outline-none focus:ring-2 focus:ring-blue-500"
            >
              <option value="">Select Size</option>
              <option v-for="size in product.sizes" :key="size" :value="size">
                {{ size }}
              </option>
            </select>
          </div>

          <!-- Action Buttons -->
          <div class="flex space-x-4 mb-8">
            <button
                @click="addToCart"
                class="flex-1 bg-black text-white py-3 px-6 rounded-md hover:bg-gray-800 transition-colors font-medium"
            >
              Add to Cart
            </button>
            <button
                @click="addToWishlist"
                class="flex-1 bg-gray-200 text-gray-800 py-3 px-6 rounded-md hover:bg-gray-300 transition-colors font-medium"
            >
              Add to Wishlist
            </button>
          </div>

          <!-- Product Info Tabs -->
          <div class="border-b border-gray-200 mb-6">
            <nav class="flex space-x-8">
              <button
                  v-for="tab in tabs"
                  :key="tab.id"
                  @click="activeTab = tab.id"
                  class="py-2 px-1 border-b-2 font-medium text-sm transition-colors"
                  :class="activeTab === tab.id
                  ? 'border-blue-500 text-blue-600'
                  : 'border-transparent text-gray-500 hover:text-gray-700'"
              >
                {{ tab.name }}
              </button>
            </nav>
          </div>

          <!-- Tab Content -->
          <div class="mb-8">
            <div v-if="activeTab === 'description'" class="text-gray-700">
              <p class="mb-4">{{ product.description }}</p>
              <ul class="space-y-2 text-sm">
                <li v-for="feature in product.features" :key="feature">• {{ feature }}</li>
              </ul>
            </div>
            <div v-else-if="activeTab === 'sizing'" class="text-gray-700">
              <p>Size guide and measurements information.</p>
            </div>
            <div v-else-if="activeTab === 'shipping'" class="text-gray-700">
              <p>Free shipping on orders over $100. Standard delivery 3-5 business days.</p>
            </div>
          </div>

          <!-- Review Section -->
          <div class="border-t border-gray-200 pt-6">
            <div class="flex items-center space-x-2 mb-4">
              <button class="flex items-center space-x-2 text-gray-600 hover:text-gray-800">
                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 20 20">
                  <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"/>
                </svg>
                <span class="text-sm">Leave a review</span>
              </button>
            </div>
          </div>
        </div>
      </div>
    </div>

    <!-- Related Products -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
      <h2 class="text-2xl font-bold text-gray-900 mb-8">Related</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div
            v-for="item in relatedProducts"
            :key="item.id"
            class="group cursor-pointer"
        >
          <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 aspect-square">
            <img
                :src="item.image"
                :alt="item.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
          </div>
          <h3 class="text-sm font-medium text-gray-900 mb-1">{{ item.name }}</h3>
          <p class="text-sm text-gray-500 mb-1">{{ item.brand }}</p>
          <p class="text-sm font-medium text-gray-900">${{ item.price }}</p>
        </div>
      </div>
    </div>

    <!-- Recommended Section -->
    <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-12 border-t border-gray-200">
      <h2 class="text-2xl font-bold text-gray-900 mb-8">Recommended</h2>
      <div class="grid grid-cols-2 md:grid-cols-4 lg:grid-cols-5 gap-6">
        <div
            v-for="item in recommendedProducts"
            :key="item.id"
            class="group cursor-pointer"
        >
          <div class="bg-gray-100 rounded-lg overflow-hidden mb-3 aspect-square">
            <img
                :src="item.image"
                :alt="item.name"
                class="w-full h-full object-cover group-hover:scale-105 transition-transform duration-300"
            />
          </div>
          <h3 class="text-sm font-medium text-gray-900 mb-1">{{ item.name }}</h3>
          <p class="text-sm text-gray-500 mb-1">{{ item.brand }}</p>
          <p class="text-sm font-medium text-gray-900">${{ item.price }}</p>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { ref, reactive } from 'vue'

export default {
  name: 'ProductPage',
  setup() {
    const selectedImageIndex = ref(0)
    const selectedSize = ref('')
    const activeTab = ref('description')

    const productImages = [
      'https://images.unsplash.com/photo-1575936123452-b67c3203c357?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D',
      'https://images.unsplash.com/photo-1575936123452-b67c3203c357?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D',
      'https://cloudinary-marketing-res.cloudinary.com/image/upload/w_1300/q_auto/f_auto/hiking_dog_mountain',
      'https://cdn.pixabay.com/photo/2024/05/26/10/15/bird-8788491_1280.jpg',
      'https://images.unsplash.com/photo-1575936123452-b67c3203c357?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D'
    ]

    const product = reactive({
      name: 'Fleece Sweater',
      price: 242.00,
      sizes: ['XS', 'S', 'M', 'L', 'XL'],
      description: 'An essential crew neck sweater from Norse & Outdoors. Features a comfortable, relaxed fit with premium fleece construction.',
      features: [
        'Premium fleece construction',
        'Comfortable crew neck',
        'Relaxed fit',
        'Ribbed cuffs and hem', 
        'Made in Germany'
      ]
    })

    const tabs = [
      { id: 'description', name: 'Description' },
      { id: 'sizing', name: 'Sizing' },
      { id: 'shipping', name: 'Shipping' }
    ]

    const relatedProducts = [
      {
        id: 1,
        name: 'Organic Cotton Tee',
        brand: 'Norse & Outdoors',
        price: 89.00,
        image: 'https://via.placeholder.com/200x250/4a4a4a/ffffff?text=Gray+Tee'
      },
      {
        id: 2,
        name: 'Denim Jacket',
        brand: 'Norse & Outdoors',
        price: 189.00,
        image: 'https://images.pexels.com/photos/414612/pexels-photo-414612.jpeg?cs=srgb&dl=pexels-souvenirpixels-414612.jpg&fm=jpg'
      }
    ]

    const recommendedProducts = [
      {
        id: 1,
        name: 'Merino Wool Scarf',
        brand: 'Norse & Outdoors',
        price: 75.00,
        image: 'https://images.ctfassets.net/hrltx12pl8hq/28ECAQiPJZ78hxatLTa7Ts/2f695d869736ae3b0de3e56ceaca3958/free-nature-images.jpg?fit=fill&w=1200&h=630'
      },
      {
        id: 2,
        name: 'Corduroy Shirt',
        brand: 'Norse & Outdoors',
        price: 129.00,
        image: 'https://via.placeholder.com/200x250/cd853f/ffffff?text=Corduroy'
      },
      {
        id: 3,
        name: 'Slim Fit Jeans',
        brand: 'Norse & Outdoors',
        price: 149.00,
        image: 'https://via.placeholder.com/200x250/000080/ffffff?text=Jeans'
      },
      {
        id: 4,
        name: 'Organic Cotton Tee',
        brand: 'Norse & Outdoors',
        price: 89.00,
        image: 'https://images.ctfassets.net/hrltx12pl8hq/28ECAQiPJZ78hxatLTa7Ts/2f695d869736ae3b0de3e56ceaca3958/free-nature-images.jpg?fit=fill&w=1200&h=630'
      },
      {
        id: 5,
        name: 'Ankle Socks',
        brand: 'Norse & Outdoors',
        price: 29.00,
        image: 'https://images.unsplash.com/photo-1575936123452-b67c3203c357?fm=jpg&q=60&w=3000&ixlib=rb-4.1.0&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8aW1hZ2V8ZW58MHx8MHx8fDA%3D'
      }
    ]

    const addToCart = () => {
      if (!selectedSize.value) {
        alert('Please select a size')
        return
      }
      alert(`Added ${product.name} (Size: ${selectedSize.value}) to cart!`)
    }

    const addToWishlist = () => {
      alert(`Added ${product.name} to wishlist!`)
    }

    return {
      selectedImageIndex,
      selectedSize,
      activeTab,
      productImages,
      product,
      tabs,
      relatedProducts,
      recommendedProducts,
      addToCart,
      addToWishlist
    }
  }
}
</script>

<style scoped>
/* Custom styles if needed */
</style>
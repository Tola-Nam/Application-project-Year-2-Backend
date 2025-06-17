<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Storeshop Dashboard</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.9.1/chart.min.js"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
    <style>
        .sidebar-item:hover {
            background-color: rgba(99, 102, 241, 0.1);
        }
        .stat-card {
            background: linear-gradient(135deg, rgba(30, 41, 59, 0.8) 0%, rgba(51, 65, 85, 0.6) 100%);
            backdrop-filter: blur(10px);
            border: 1px solid rgba(255, 255, 255, 0.1);
        }
    </style>
</head>
<body class="bg-gray-900 text-white min-h-screen">
    <!-- Sidebar -->
    <div class="fixed left-0 top-0 h-full w-64 bg-gray-800 border-r border-gray-700 z-50 transform -translate-x-full lg:translate-x-0 transition-transform duration-300" id="sidebar">
        <!-- Logo -->
        <div class="flex items-center px-6 py-4 border-b border-gray-700">
            <div class="flex items-center space-x-2">
                <div class="w-8 h-8 bg-indigo-600 rounded-lg flex items-center justify-center">
                    <i class="fas fa-store text-white text-sm"></i>
                </div>
                <span class="text-xl font-bold">Storeshop</span>
            </div>
        </div>

        <!-- Store Selector -->
        <div class="px-4 py-3 border-b border-gray-700">
            <select class="w-full bg-gray-700 text-white px-3 py-2 rounded-lg border border-gray-600 focus:outline-none focus:border-indigo-500">
                <option>Banana Store</option>
                <option>Apple Store</option>
                <option>Orange Store</option>
            </select>
        </div>

        <!-- Navigation -->
        <nav class="mt-4">
            <div class="px-4 space-y-1">
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-white bg-indigo-600 rounded-lg" data-route="dashboard">
                    <i class="fas fa-chart-line mr-3"></i>
                    Dashboard
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="products">
                    <i class="fas fa-box mr-3"></i>
                    Products
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="conversation">
                    <i class="fas fa-comments mr-3"></i>
                    Conversation
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg relative" data-route="analytics">
                    <i class="fas fa-chart-bar mr-3"></i>
                    Analytics
                    <span class="absolute right-2 bg-orange-500 text-xs px-2 py-1 rounded-full">8</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg relative" data-route="campaigns">
                    <i class="fas fa-bullhorn mr-3"></i>
                    Campaigns
                    <span class="absolute right-2 bg-orange-500 text-xs px-2 py-1 rounded-full">1</span>
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="audience">
                    <i class="fas fa-users mr-3"></i>
                    Audience
                </a>
                <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="statistics">
                    <i class="fas fa-chart-pie mr-3"></i>
                    Statistics
                </a>
            </div>

            <div class="mt-8 px-4">
                <div class="border-t border-gray-700 pt-4 space-y-1">
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="settings">
                        <i class="fas fa-cog mr-3"></i>
                        Settings
                    </a>
                    <a href="#" class="sidebar-item flex items-center px-4 py-3 text-gray-300 hover:text-white rounded-lg" data-route="help">
                        <i class="fas fa-question-circle mr-3"></i>
                        Help Center
                    </a>
                </div>
            </div>
        </nav>

        <!-- Dark Mode Toggle -->
        <div class="absolute bottom-4 left-4 right-4">
            <div class="flex items-center justify-between">
                <span class="text-sm text-gray-400">Dark Mode</span>
                <div class="relative">
                    <input type="checkbox" class="sr-only" checked>
                    <div class="w-10 h-6 bg-indigo-600 rounded-full shadow-inner"></div>
                    <div class="absolute w-4 h-4 bg-white rounded-full shadow top-1 right-1 transition-transform"></div>
                </div>
            </div>
        </div>
    </div>

    <!-- Mobile Sidebar Overlay -->
    <div class="fixed inset-0 bg-black bg-opacity-50 z-40 lg:hidden hidden" id="sidebar-overlay"></div>

    <!-- Main Content -->
    <div class="lg:ml-64">
        <!-- Top Bar -->
        <header class="bg-gray-800 border-b border-gray-700 px-4 lg:px-6 py-4">
            <div class="flex items-center justify-between">
                <!-- Mobile Menu Button -->
                <button class="lg:hidden text-gray-400 hover:text-white" id="mobile-menu-btn">
                    <i class="fas fa-bars text-xl"></i>
                </button>

                <!-- Search Bar -->
                <div class="flex-1 max-w-md mx-4">
                    <div class="relative">
                        <i class="fas fa-search absolute left-3 top-1/2 transform -translate-y-1/2 text-gray-400"></i>
                        <input type="text" placeholder="Search campaign, customer, etc..." 
                               class="w-full bg-gray-700 text-white pl-10 pr-4 py-2 rounded-lg border border-gray-600 focus:outline-none focus:border-indigo-500">
                    </div>
                </div>

                <!-- Right Section -->
                <div class="flex items-center space-x-4">
                    <button class="text-gray-400 hover:text-white relative">
                        <i class="fas fa-bell text-xl"></i>
                        <span class="absolute -top-1 -right-1 bg-red-500 text-xs w-5 h-5 rounded-full flex items-center justify-center">3</span>
                    </button>
                    
                    <div class="flex items-center space-x-3">
                        <div class="w-8 h-8 bg-gradient-to-r from-indigo-500 to-purple-600 rounded-full flex items-center justify-center">
                            <span class="text-sm font-semibold">MJ</span>
                        </div>
                        <div class="hidden md:block">
                            <div class="text-sm font-medium">Manik Jingga</div>
                            <div class="text-xs text-gray-400">Admin Store</div>
                        </div>
                        <i class="fas fa-chevron-down text-gray-400 text-sm"></i>
                    </div>
                </div>
            </div>
        </header>

        <!-- Dashboard Content -->
        <main class="p-4 lg:p-6" id="main-content">
            <!-- Dashboard Route -->
            <div id="dashboard-content">
                <!-- Page Header -->
                <div class="flex flex-col lg:flex-row lg:items-center lg:justify-between mb-6">
                    <div>
                        <h1 class="text-2xl lg:text-3xl font-bold">Dashboard</h1>
                        <p class="text-gray-400 mt-1">Here's your analytic details</p>
                    </div>
                    <div class="flex items-center space-x-3 mt-4 lg:mt-0">
                        <button class="flex items-center px-4 py-2 bg-gray-700 text-white rounded-lg hover:bg-gray-600">
                            <i class="fas fa-filter mr-2"></i>
                            Filter by
                        </button>
                        <button class="flex items-center px-4 py-2 bg-indigo-600 text-white rounded-lg hover:bg-indigo-700">
                            <i class="fas fa-download mr-2"></i>
                            Exports
                        </button>
                    </div>
                </div>

                <!-- Stats Cards -->
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-4 gap-6 mb-8">
                    <!-- Total Sales -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                <span class="text-gray-300 text-sm">Total Sales</span>
                            </div>
                            <i class="fas fa-ellipsis-h text-gray-400"></i>
                        </div>
                        <div class="mb-2">
                            <span class="text-2xl lg:text-3xl font-bold">$120,784.02</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                            <span class="text-green-400">12.3%</span>
                            <span class="text-gray-400 ml-1">+$1,453.87 today</span>
                        </div>
                        <button class="text-indigo-400 text-sm mt-4 hover:underline">View Report →</button>
                    </div>

                    <!-- Total Orders -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-blue-500 rounded-full mr-2"></div>
                                <span class="text-gray-300 text-sm">Total Orders</span>
                            </div>
                            <i class="fas fa-ellipsis-h text-gray-400"></i>
                        </div>
                        <div class="mb-2">
                            <span class="text-2xl lg:text-3xl font-bold">28,834</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                            <span class="text-green-400">20.1%</span>
                            <span class="text-gray-400 ml-1">+2,676 today</span>
                        </div>
                        <button class="text-indigo-400 text-sm mt-4 hover:underline">View Report →</button>
                    </div>

                    <!-- Visitors -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-yellow-500 rounded-full mr-2"></div>
                                <span class="text-gray-300 text-sm">Visitor</span>
                            </div>
                            <i class="fas fa-ellipsis-h text-gray-400"></i>
                        </div>
                        <div class="mb-2">
                            <span class="text-2xl lg:text-3xl font-bold">18,896</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-down text-red-400 mr-1"></i>
                            <span class="text-red-400">5.6%</span>
                            <span class="text-gray-400 ml-1">-876 today</span>
                        </div>
                        <button class="text-indigo-400 text-sm mt-4 hover:underline">View Report →</button>
                    </div>

                    <!-- Refunded -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-4">
                            <div class="flex items-center">
                                <div class="w-2 h-2 bg-purple-500 rounded-full mr-2"></div>
                                <span class="text-gray-300 text-sm">Refunded</span>
                            </div>
                            <i class="fas fa-ellipsis-h text-gray-400"></i>
                        </div>
                        <div class="mb-2">
                            <span class="text-2xl lg:text-3xl font-bold">2,876</span>
                        </div>
                        <div class="flex items-center text-sm">
                            <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                            <span class="text-green-400">1.3%</span>
                            <span class="text-gray-400 ml-1">+34 today</span>
                        </div>
                        <button class="text-indigo-400 text-sm mt-4 hover:underline">View Report →</button>
                    </div>
                </div>

                <!-- Charts Section -->
                <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
                    <!-- Revenue Chart -->
                    <div class="lg:col-span-2 stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <div>
                                <h3 class="text-lg font-semibold mb-1">Revenue</h3>
                                <div class="flex items-center">
                                    <span class="text-2xl font-bold">$16,400</span>
                                    <span class="text-gray-400 text-sm ml-2">12</span>
                                    <div class="flex items-center ml-4">
                                        <i class="fas fa-arrow-up text-green-400 mr-1"></i>
                                        <span class="text-green-400 text-sm">+10%</span>
                                    </div>
                                </div>
                            </div>
                            <select class="bg-gray-700 text-white px-3 py-1 rounded border border-gray-600">
                                <option>Month</option>
                                <option>Week</option>
                                <option>Year</option>
                            </select>
                        </div>
                        <div class="h-64">
                            <canvas id="revenueChart"></canvas>
                        </div>
                        <div class="flex items-center justify-center mt-4 space-x-6">
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-indigo-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-300">Profit</span>
                            </div>
                            <div class="flex items-center">
                                <div class="w-3 h-3 bg-gray-500 rounded-full mr-2"></div>
                                <span class="text-sm text-gray-300">Loss</span>
                            </div>
                        </div>
                    </div>

                    <!-- Traffic Channel -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">Traffic Channel</h3>
                            <select class="bg-gray-700 text-white px-3 py-1 rounded border border-gray-600 text-sm">
                                <option>All time</option>
                                <option>Last 30 days</option>
                                <option>Last 7 days</option>
                            </select>
                        </div>
                        <div class="flex items-center justify-center mb-6">
                            <div class="relative w-32 h-32">
                                <canvas id="trafficChart"></canvas>
                                <div class="absolute inset-0 flex items-center justify-center">
                                    <span class="text-2xl font-bold">19%</span>
                                </div>
                            </div>
                        </div>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-indigo-500 rounded-full mr-3"></div>
                                    <span class="text-sm">Direct</span>
                                </div>
                                <span class="text-sm font-semibold">50.5%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-gray-500 rounded-full mr-3"></div>
                                    <span class="text-sm">Referral</span>
                                </div>
                                <span class="text-sm font-semibold">30.5%</span>
                            </div>
                            <div class="flex items-center justify-between">
                                <div class="flex items-center">
                                    <div class="w-3 h-3 bg-purple-500 rounded-full mr-3"></div>
                                    <span class="text-sm">Organic</span>
                                </div>
                                <span class="text-sm font-semibold">19%</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Recent Activity and Customer Tables -->
                <div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mt-8">
                    <!-- Recent Activity -->
                    <div class="stat-card rounded-xl p-6">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-lg font-semibold">Recent Activity</h3>
                            <select class="bg-gray-700 text-white px-3 py-1 rounded border border-gray-600 text-sm">
                                <option>Last 24h</option>
                                <option>Last 7 days</option>
                                <option>Last 30 days</option>
                            </select>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr class="text-gray-400 text-sm">
                                        <th class="text-left pb-3">Customer</th>
                                        <th class="text-left pb-3">Status</th>
                                        <th class="text-left pb-3">Customer ID</th>
                                        <th class="text-left pb-3">Retained</th>
                                        <th class="text-left pb-3">Amount</th>
                                    </tr>
                                </thead>
                                <tbody class="space-y-3">
                                    <tr class="border-b border-gray-700">
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-sm">RR</span>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Ronald Richards</div>
                                                    <div class="text-sm text-gray-400">ronald@email.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 bg-green-900 text-green-300 rounded text-xs">Member</span>
                                        </td>
                                        <td class="py-3 text-sm">#74568320</td>
                                        <td class="py-3 text-sm">8 min ago</td>
                                        <td class="py-3 text-sm font-medium">$12,408.20</td>
                                    </tr>
                                    <tr class="border-b border-gray-700">
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-sm">DS</span>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Darrell Steward</div>
                                                    <div class="text-sm text-gray-400">steward.darrell@email.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 bg-yellow-900 text-yellow-300 rounded text-xs">Signed Up</span>
                                        </td>
                                        <td class="py-3 text-sm">#23134855</td>
                                        <td class="py-3 text-sm">10 min ago</td>
                                        <td class="py-3 text-sm font-medium">$201.50</td>
                                    </tr>
                                    <tr>
                                        <td class="py-3">
                                            <div class="flex items-center">
                                                <div class="w-8 h-8 bg-gray-600 rounded-full flex items-center justify-center mr-3">
                                                    <span class="text-sm">MM</span>
                                                </div>
                                                <div>
                                                    <div class="font-medium">Marvin McKinney</div>
                                                    <div class="text-sm text-gray-400">mckinney.marvin@email.com</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td class="py-3">
                                            <span class="px-2 py-1 bg-blue-900 text-blue-300 rounded text-xs">New Customer</span>
                                        </td>
                                        <td class="py-3 text-sm">#54394837</td>
                                        <td class="py-3 text-sm">15 min ago</td>
                                        <td class="py-3 text-sm font-medium">$2,856.03</td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Placeholder for another chart or content -->
                    <div class="stat-card rounded-xl p-6">
                        <h3 class="text-lg font-semibold mb-6">Performance Overview</h3>
                        <div class="space-y-4">
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Conversion Rate</span>
                                <span class="text-green-400 font-semibold">3.2%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-green-400 h-2 rounded-full" style="width: 32%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Average Order Value</span>
                                <span class="text-blue-400 font-semibold">$124.50</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-blue-400 h-2 rounded-full" style="width: 68%"></div>
                            </div>
                            
                            <div class="flex items-center justify-between">
                                <span class="text-gray-300">Customer Retention</span>
                                <span class="text-purple-400 font-semibold">87.5%</span>
                            </div>
                            <div class="w-full bg-gray-700 rounded-full h-2">
                                <div class="bg-purple-400 h-2 rounded-full" style="width: 87%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Other Route Contents (hidden by default) -->
            <div id="products-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Products</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Products management interface will be here...</p>
                </div>
            </div>

            <div id="conversation-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Conversations</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Customer conversations interface will be here...</p>
                </div>
            </div>

            <div id="analytics-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Analytics</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Advanced analytics dashboard will be here...</p>
                </div>
            </div>

            <div id="campaigns-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Campaigns</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Marketing campaigns management will be here...</p>
                </div>
            </div>

            <div id="audience-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Audience</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Audience segmentation and insights will be here...</p>
                </div>
            </div>

            <div id="statistics-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Statistics</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Detailed statistics and reports will be here...</p>
                </div>
            </div>

            <div id="settings-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Settings</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Application settings will be here...</p>
                </div>
            </div>

            <div id="help-content" class="hidden">
                <h1 class="text-3xl font-bold mb-6">Help Center</h1>
                <div class="stat-card rounded-xl p-6">
                    <p class="text-gray-300">Help documentation and support will be here...</p>
                </div>
            </div>
        </main>
    </div>
</body>
</html>
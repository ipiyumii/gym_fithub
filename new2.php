<?php
include("./config.php"); // Database connection

// Initialize messages
$success_message = "";
$error_message = "";

// Fetch all trainers
$trainers = [];
$sql = "SELECT * FROM trainers ORDER BY full_name";
$result = mysqli_query($conn, $sql);

if ($result) {
    while ($row = mysqli_fetch_assoc($result)) {
        $trainers[] = $row;
    }
} else {
    $error_message = "Error fetching trainers: " . mysqli_error($conn);
}

include("./includes/header.php");
?>

<!-- Main Content -->
<div class="flex-1 overflow-x-hidden overflow-y-auto">
    <!-- Top Navigation -->
    <header class="bg-white shadow-sm">
        <div class="flex items-center justify-between px-6 py-3">
            <button class="md:hidden focus:outline-none">
                <i class="fas fa-bars text-gray-600 text-lg"></i>
            </button>

            <div class="flex items-center space-x-4">
                <div class="relative">
                    <button class="focus:outline-none">
                        <i class="fas fa-bell text-gray-600 text-lg"></i>
                        <span class="absolute top-0 right-0 -mt-1 -mr-1 bg-red-500 text-white text-xs rounded-full h-4 w-4 flex items-center justify-center">3</span>
                    </button>
                </div>

                <div class="flex items-center space-x-2">
                    <div class="h-8 w-8 rounded-full bg-blue-500 flex items-center justify-center text-white font-semibold">A</div>
                    <span class="text-gray-700"></span>
                </div>
            </div>
        </div>
    </header>

    <!-- Page Content -->
    <main class="p-6">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Trainers</h1>
            
        </div>

        <?php if (!empty($success_message)): ?>
            <div class="bg-green-100 border-l-4 border-green-500 text-green-700 p-4 mb-6" role="alert">
                <p><?php echo $success_message; ?></p>
            </div>
        <?php endif; ?>

        <?php if (!empty($error_message)): ?>
            <div class="bg-red-100 border-l-4 border-red-500 text-red-700 p-4 mb-6" role="alert">
                <p><?php echo $error_message; ?></p>
            </div>
        <?php endif; ?>

        <!-- Search and Filter -->
        <div class="bg-white rounded-lg shadow-sm p-4 mb-6">
            <div class="flex flex-col md:flex-row md:items-center md:justify-between space-y-3 md:space-y-0">
                <div class="flex items-center space-x-2">
                    <div class="relative">
                        <input type="text" id="search-trainers" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 block w-full pl-10 p-2.5" placeholder="Search trainers...">
                        <div class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                    <button id="search-button" class="bg-blue-50 hover:bg-blue-100 text-blue-600 py-2.5 px-5 rounded-lg transition">
                        Search
                    </button>
                </div>
                <div class="flex items-center space-x-2">
                    <select id="status-filter" class="bg-gray-50 border border-gray-300 text-gray-900 text-sm rounded-lg focus:ring-blue-500 focus:border-blue-500 p-2.5">
                        <option value="">All Status</option>
                        <option value="active">Active</option>
                        
                    </select>
                    <button id="filter-button" class="bg-gray-200 hover:bg-gray-300 text-gray-700 py-2.5 px-5 rounded-lg transition">
                        Filter
                    </button>
                </div>
            </div>
        </div>

        <!-- Trainers Grid -->
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6 mb-8">
            <?php foreach ($trainers as $trainer): ?>
                <div class="trainer-card bg-white rounded-lg shadow-sm overflow-hidden" data-status="<?php echo htmlspecialchars($trainer['status']); ?>">
                    <div class="relative h-48 bg-gray-200">
                        <?php if (!empty($trainer['image_url']) && file_exists($trainer['image_url'])): ?>
                            <img src="<?php echo htmlspecialchars($trainer['image_url']); ?>" alt="<?php echo htmlspecialchars($trainer['full_name']); ?>" class="w-full h-full object-cover">
                        <?php else: ?>
                            <div class="w-full h-full flex items-center justify-center bg-gray-100">
                                <i class="fas fa-user-tie text-gray-400 text-5xl"></i>
                            </div>
                        <?php endif; ?>

                        <?php if ($trainer['status'] === 'active'): ?>
                            <span class="absolute top-4 right-4 px-2 py-1 bg-green-100 text-green-800 text-xs font-semibold rounded-full">Active</span>
                        <?php else: ?>
                            <span class="absolute top-4 right-4 px-2 py-1 bg-red-100 text-red-800 text-xs font-semibold rounded-full">Inactive</span>
                        <?php endif; ?>
                    </div>
                    <div class="p-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-1"><?php echo htmlspecialchars($trainer['full_name']); ?></h3>
                        <p class="text-gray-600 mb-4"><?php echo htmlspecialchars($trainer['specialty']); ?></p>
                        <div class="mb-4 truncate">
                            <p class="text-sm text-gray-600"><?php echo substr(htmlspecialchars($trainer['bio']), 0, 100); ?>...</p>
                        </div>
                        <div class="flex justify-between items-center">
                            <p class="text-blue-600 font-bold">$<?php echo number_format($trainer['price_per_session'], 2); ?><span class="text-gray-500 text-sm"> / session</span></p>
                            <div class="flex space-x-2">
                                <a href="admin_edit_trainer.php?id=<?php echo $trainer['id']; ?>" class="text-indigo-600 hover:text-indigo-900 hidden" title="Edit">
                                    <i class="fas fa-edit"></i>
                                </a>
                                <a href="admin_trainers.php?toggle_status=<?php echo $trainer['id']; ?>" class="hidden <?php echo $trainer['status'] === 'active' ? 'text-yellow-600 hover:text-yellow-900' : 'text-green-600 hover:text-green-900'; ?>" title="<?php echo $trainer['status'] === 'active' ? 'Deactivate' : 'Activate'; ?>">
                                    <i class="fas <?php echo $trainer['status'] === 'active' ? 'fa-ban' : 'fa-check-circle'; ?>"></i>
                                </a>
                                <a href="#" class="text-red-600 hover:text-red-900 delete-trainer hidden" data-id="<?php echo $trainer['id']; ?>" title="Delete">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            <?php endforeach; ?>

            <?php if (empty($trainers)): ?>
                <div class="col-span-full text-center py-8">
                    <p class="text-gray-500 mb-4">No trainers found.</p>
                    <a href="admin_add_trainer.php" class="inline-flex items-center px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition">
                        <i class="fas fa-plus mr-2"></i> Add New Trainer
                    </a>
                </div>
            <?php endif; ?>
        </div>

        <!-- Delete Confirmation Modal -->
        <div id="delete-modal" class="hidden fixed inset-0 bg-gray-600 bg-opacity-50 flex items-center justify-center p-4 z-50">
            <div class="bg-white rounded-lg shadow-lg max-w-md w-full">
                <div class="p-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Confirm Deletion</h3>
                    <p class="text-gray-600 mb-6">Are you sure you want to delete this trainer? This action cannot be undone.</p>
                    <div class="flex justify-end space-x-3">
                        <button id="cancel-delete" class="bg-gray-200 text-gray-700 py-2 px-4 rounded-lg hover:bg-gray-300 transition">Cancel</button>
                        <a href="#" id="confirm-delete" class="bg-red-600 text-white py-2 px-4 rounded-lg hover:bg-red-700 transition">Delete</a>
                    </div>
                </div>
            </div>
        </div>
    </main>
</div>

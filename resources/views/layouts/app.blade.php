<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Suarez Task Manager</title>
    <!-- Tailwind CSS CDN -->
    <script src="https://cdn.tailwindcss.com"></script>
    <script>
        tailwind.config = {
            darkMode: 'class',
            theme: {
                extend: {
                    colors: {
                        ink: '#171717',
                        paper: '#fafafa',
                        line: '#e5e5e5'
                    }
                }
            }
        }
    </script>
    <!-- FontAwesome for Icons -->
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <!-- Google Fonts Inter -->
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        ::-webkit-scrollbar { width: 8px; height: 8px; }
        ::-webkit-scrollbar-track { background: transparent; }
        ::-webkit-scrollbar-thumb { background: #d4d4d4; border-radius: 4px; }
        ::-webkit-scrollbar-thumb:hover { background: #b3b3b3; }
        .dark ::-webkit-scrollbar-thumb { background: #404040; }
        .dark ::-webkit-scrollbar-thumb:hover { background: #525252; }

        .task-square { aspect-ratio: 1 / 1; }

        /* Smooth theme transition */
        body, header, main, #taskModal, .task-square, input, select, textarea, button {
            transition: background-color 0.2s ease, border-color 0.2s ease, color 0.2s ease;
        }
    </style>
</head>
<body class="bg-paper dark:bg-neutral-950 text-ink dark:text-neutral-100 min-h-screen">

    <!-- Header -->
    <header class="border-b border-line dark:border-neutral-800 sticky top-0 bg-paper/95 dark:bg-neutral-950/95 backdrop-blur-sm z-10">
        <div class="max-w-6xl mx-auto px-6 md:px-10 h-20 flex items-center justify-between gap-4">
            <div class="flex items-center gap-3">
                <div class="w-9 h-9 rounded-lg border border-line dark:border-neutral-800 flex items-center justify-center">
                    <i class="fa-solid fa-layer-group text-sm"></i>
                </div>
                <h1 class="font-semibold text-base">Suarez TaskForge</h1>
            </div>

            <div class="flex items-center gap-3 flex-1 max-w-sm">
                <div class="relative w-full">
                    <span class="absolute inset-y-0 left-0 flex items-center pl-3 pointer-events-none text-neutral-400">
                        <i class="fa-solid fa-magnifying-glass text-xs"></i>
                    </span>
                    <input type="text" id="searchInput" oninput="handleSearch()" placeholder="Search tasks..." class="w-full border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg pl-9 pr-3 py-2 text-sm placeholder-neutral-400 focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors">
                </div>
            </div>

            <button onclick="toggleTheme()" id="themeToggle" title="Toggle theme" class="w-9 h-9 rounded-lg border border-line dark:border-neutral-800 flex items-center justify-center text-neutral-500 hover:text-ink dark:hover:text-white transition-colors">
                <i id="themeIcon" class="fa-solid fa-moon text-sm"></i>
            </button>

            <button onclick="openCreateModal()" class="flex items-center gap-2 bg-ink dark:bg-white text-white dark:text-ink font-medium px-4 py-2 rounded-lg text-sm hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-colors">
                <i class="fa-solid fa-plus text-xs"></i>
                <span>New Task</span>
            </button>
        </div>
    </header>

    <!-- Dynamic Flash Notification Banner -->
    <div id="flashMessage" class="hidden max-w-6xl mx-auto mt-6 px-6 md:px-10">
        <div class="border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-4 py-3 flex items-center gap-2.5 text-sm">
            <i id="flashIcon" class="fa-solid text-xs"></i>
            <span id="flashText" class="font-medium"></span>
        </div>
    </div>

    <!-- Main Content Area -->
    <main class="max-w-6xl mx-auto px-6 md:px-10 py-10">

        <!-- Filter Row -->
        <div class="flex flex-wrap items-center justify-between gap-4 mb-8">
            <div class="flex items-center gap-1 border border-line dark:border-neutral-800 rounded-lg p-1">
                <button onclick="filterTasks('all')" id="nav-all" class="px-3 py-1.5 rounded-md text-xs font-medium bg-ink dark:bg-white text-white dark:text-ink transition-colors">All <span id="count-all">0</span></button>
                <button onclick="filterTasks('pending')" id="nav-pending" class="px-3 py-1.5 rounded-md text-xs font-medium text-neutral-500 hover:text-ink dark:hover:text-white transition-colors">Pending <span id="count-pending">0</span></button>
                <button onclick="filterTasks('completed')" id="nav-completed" class="px-3 py-1.5 rounded-md text-xs font-medium text-neutral-500 hover:text-ink dark:hover:text-white transition-colors">Completed <span id="count-completed">0</span></button>
            </div>

            <select id="priorityFilter" onchange="applyFilters()" class="border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-3 py-2 text-xs text-neutral-600 dark:text-neutral-300 focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors">
                <option value="all">All Priorities</option>
                <option value="High">High Priority</option>
                <option value="Medium">Medium Priority</option>
                <option value="Low">Low Priority</option>
            </select>
        </div>

        <!-- Empty State (Hidden by default) -->
        <div id="emptyState" class="hidden py-24 text-center border border-dashed border-line dark:border-neutral-800 rounded-xl">
            <div class="w-12 h-12 mx-auto mb-4 rounded-lg border border-line dark:border-neutral-800 flex items-center justify-center text-neutral-400 text-lg">
                <i class="fa-solid fa-folder-open"></i>
            </div>
            <h3 class="text-sm font-medium text-neutral-600 dark:text-neutral-300">No tasks found</h3>
            <p class="text-xs text-neutral-400 mt-1">Create a task to get started.</p>
        </div>

        <!-- Square Task Grid -->
        <div id="taskGrid" class="grid grid-cols-2 sm:grid-cols-3 lg:grid-cols-4 gap-4">
            <!-- Injected dynamically via JS -->
        </div>

    </main>

    <!-- Task Modal — doubles as Create / View / Edit / Delete -->
    <div id="taskModal" class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/30 dark:bg-black/60 hidden opacity-0 transition-opacity duration-150">
        <div class="bg-white dark:bg-neutral-900 border border-line dark:border-neutral-800 w-full max-w-md rounded-xl overflow-hidden transform scale-95 transition-transform duration-150" id="modalCard">

            <!-- View Mode -->
            <div id="viewMode">
                <div class="flex items-center justify-between px-6 py-5 border-b border-line dark:border-neutral-800">
                    <span id="viewStatusBadge" class="text-xs font-medium px-2.5 py-1 rounded-md border border-line dark:border-neutral-800"></span>
                    <button onclick="closeModal()" class="w-7 h-7 rounded-md border border-line dark:border-neutral-800 text-neutral-400 hover:text-ink dark:hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                <div class="px-6 py-5">
                    <h3 id="viewTitle" class="font-semibold text-lg text-ink dark:text-white mb-2"></h3>
                    <p id="viewDesc" class="text-sm text-neutral-500 dark:text-neutral-400 leading-relaxed mb-5"></p>
                    <div class="flex items-center gap-4 text-xs text-neutral-500 dark:text-neutral-400">
                        <span class="flex items-center gap-1.5"><i class="fa-regular fa-calendar"></i> <span id="viewDueDate"></span></span>
                        <span id="viewPriorityBadge" class="px-2 py-0.5 rounded-md border border-line dark:border-neutral-800 font-medium"></span>
                    </div>
                </div>
                <div class="px-6 py-4 border-t border-line dark:border-neutral-800 flex items-center gap-2">
                    <button onclick="toggleTaskStatus(currentTaskId)" class="flex-1 py-2.5 rounded-lg border border-line dark:border-neutral-800 text-xs font-medium hover:bg-neutral-50 dark:hover:bg-neutral-800 flex items-center justify-center gap-1.5 transition-colors">
                        <i id="viewToggleIcon" class="fa-solid text-xs"></i> <span id="viewToggleLabel"></span>
                    </button>
                    <button onclick="openEditModal(currentTaskId)" class="w-10 h-10 rounded-lg border border-line dark:border-neutral-800 text-neutral-500 hover:text-ink dark:hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-pen text-xs"></i>
                    </button>
                    <button onclick="deleteTask(currentTaskId)" class="w-10 h-10 rounded-lg border border-line dark:border-neutral-800 text-neutral-500 hover:text-rose-600 flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-trash-can text-xs"></i>
                    </button>
                </div>
            </div>

            <!-- Create / Edit Form Mode -->
            <div id="formMode" class="hidden">
                <div class="flex items-center justify-between px-6 py-5 border-b border-line dark:border-neutral-800">
                    <h3 id="formTitle" class="font-semibold text-ink dark:text-white text-sm"></h3>
                    <button onclick="closeModal()" class="w-7 h-7 rounded-md border border-line dark:border-neutral-800 text-neutral-400 hover:text-ink dark:hover:text-white flex items-center justify-center transition-colors">
                        <i class="fa-solid fa-xmark text-xs"></i>
                    </button>
                </div>
                <form id="taskForm" onsubmit="handleFormSubmit(event)" class="p-6 space-y-4">
                    <input type="hidden" id="taskId">
                    <div>
                        <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1.5">Task Title *</label>
                        <input type="text" id="taskTitle" required placeholder="e.g., Redesign landing page header..." class="w-full border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-3.5 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors">
                    </div>
                    <div>
                        <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1.5">Description</label>
                        <textarea id="taskDesc" rows="3" placeholder="Add extra context, checklists, or specifications..." class="w-full border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-3.5 py-2.5 text-sm placeholder-neutral-400 focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors resize-none"></textarea>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1.5">Priority</label>
                            <select id="taskPriority" class="w-full border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors">
                                <option value="Low">Low</option>
                                <option value="Medium" selected>Medium</option>
                                <option value="High">High</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-neutral-500 dark:text-neutral-400 mb-1.5">Due Date *</label>
                            <input type="date" id="taskDueDate" required class="w-full border border-line dark:border-neutral-800 bg-white dark:bg-neutral-900 rounded-lg px-3.5 py-2.5 text-sm focus:outline-none focus:border-ink dark:focus:border-neutral-500 transition-colors">
                        </div>
                    </div>
                    <div class="pt-4 border-t border-line dark:border-neutral-800 flex items-center justify-end gap-3">
                        <button type="button" onclick="handleFormCancel()" class="px-4 py-2.5 rounded-lg border border-line dark:border-neutral-800 text-neutral-600 dark:text-neutral-300 hover:text-ink dark:hover:text-white font-medium text-sm transition-colors">Cancel</button>
                        <button type="submit" class="px-5 py-2.5 rounded-lg bg-ink dark:bg-white text-white dark:text-ink font-medium text-sm hover:bg-neutral-800 dark:hover:bg-neutral-200 transition-colors">Save Task</button>
                    </div>
                </form>
            </div>

        </div>
    </div>

    <!-- Application Logic Script -->
    <script>
        // Task store — starts empty; no seeded sample data
        let tasks = [];

        let currentFilter = 'all';
        let currentSearchQuery = '';
        let currentTaskId = null;   // task currently open in the modal
        let isDark = false;         // current theme state, in-memory only

        // Initialize App on Window Load
        window.onload = function() {
            const todayStr = new Date().toISOString().split('T')[0];
            document.getElementById('taskDueDate').min = todayStr;

            // Respect the visitor's OS-level preference on first load
            isDark = window.matchMedia && window.matchMedia('(prefers-color-scheme: dark)').matches;
            applyTheme();

            renderApp();
        };

        // --- Theme ---

        function toggleTheme() {
            isDark = !isDark;
            applyTheme();
        }

        function applyTheme() {
            document.documentElement.classList.toggle('dark', isDark);
            const icon = document.getElementById('themeIcon');
            icon.className = isDark ? 'fa-solid fa-sun text-sm' : 'fa-solid fa-moon text-sm';
        }

        // Render application components
        function renderApp() {
            renderTaskGrid();
            updateSidebarCounts();
        }

        // Update filter badge counts
        function updateSidebarCounts() {
            document.getElementById('count-all').innerText = tasks.length;
            document.getElementById('count-pending').innerText = tasks.filter(t => t.status === 'pending').length;
            document.getElementById('count-completed').innerText = tasks.filter(t => t.status === 'completed').length;
        }

        // Filter handler from top bar
        function filterTasks(filter) {
            currentFilter = filter;
            ['all', 'pending', 'completed'].forEach(f => {
                const el = document.getElementById(`nav-${f}`);
                if (f === filter) {
                    el.className = "px-3 py-1.5 rounded-md text-xs font-medium bg-ink dark:bg-white text-white dark:text-ink transition-colors";
                } else {
                    el.className = "px-3 py-1.5 rounded-md text-xs font-medium text-neutral-500 hover:text-ink dark:hover:text-white transition-colors";
                }
            });
            renderTaskGrid();
        }

        // Search handler
        function handleSearch() {
            currentSearchQuery = document.getElementById('searchInput').value.toLowerCase().trim();
            renderTaskGrid();
        }

        // Priority filter change
        function applyFilters() {
            renderTaskGrid();
        }

        // Core filtered list generator
        function getFilteredTasks() {
            const priorityVal = document.getElementById('priorityFilter').value;
            return tasks.filter(task => {
                if (currentFilter !== 'all' && task.status !== currentFilter) return false;
                if (priorityVal !== 'all' && task.priority !== priorityVal) return false;
                if (currentSearchQuery && !task.title.toLowerCase().includes(currentSearchQuery) && !task.description.toLowerCase().includes(currentSearchQuery)) return false;
                return true;
            });
        }

        // Render the square task grid
        function renderTaskGrid() {
            const filtered = getFilteredTasks();
            const grid = document.getElementById('taskGrid');
            const emptyState = document.getElementById('emptyState');

            grid.innerHTML = '';

            if (filtered.length === 0) {
                emptyState.classList.remove('hidden');
                grid.classList.add('hidden');
                return;
            } else {
                emptyState.classList.add('hidden');
                grid.classList.remove('hidden');
            }

            filtered.forEach(task => {
                let priorityDot = 'bg-neutral-400';
                if (task.priority === 'High') priorityDot = 'bg-rose-500';
                if (task.priority === 'Medium') priorityDot = 'bg-amber-500';
                if (task.priority === 'Low') priorityDot = 'bg-emerald-500';

                const isCompleted = task.status === 'completed';

                const square = document.createElement('button');
                square.type = 'button';
                square.onclick = () => openViewModal(task.id);
                square.className = "task-square group border border-line dark:border-neutral-800 rounded-xl p-4 flex flex-col justify-between text-left hover:border-ink dark:hover:border-neutral-500 transition-colors bg-white dark:bg-neutral-900";
                square.innerHTML = `
                    <div class="flex items-center justify-between">
                        <span class="w-2 h-2 rounded-full ${priorityDot}"></span>
                        ${isCompleted ? '<i class="fa-solid fa-circle-check text-emerald-500 text-sm"></i>' : ''}
                    </div>
                    <div>
                        <h4 class="font-medium text-sm text-ink dark:text-white line-clamp-3 ${isCompleted ? 'line-through text-neutral-400 dark:text-neutral-500' : ''}">${escapeHtml(task.title)}</h4>
                        <p class="text-[11px] text-neutral-400 dark:text-neutral-500 mt-1.5">${task.dueDate}</p>
                    </div>
                `;
                grid.appendChild(square);
            });
        }

        // --- Modal control ---

        function showModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.remove('hidden');
            setTimeout(() => {
                modal.classList.remove('opacity-0');
                card.classList.remove('scale-95');
                card.classList.add('scale-100');
            }, 10);
        }

        function closeModal() {
            const modal = document.getElementById('taskModal');
            const card = document.getElementById('modalCard');
            modal.classList.add('opacity-0');
            card.classList.remove('scale-100');
            card.classList.add('scale-95');
            setTimeout(() => {
                modal.classList.add('hidden');
                currentTaskId = null;
            }, 150);
        }

        // Open a task in read-only View mode (clicking a square) — this is where all CRUD actions live
        function openViewModal(id) {
            const task = tasks.find(t => t.id === id);
            if (!task) return;
            currentTaskId = id;

            document.getElementById('viewMode').classList.remove('hidden');
            document.getElementById('formMode').classList.add('hidden');

            const isCompleted = task.status === 'completed';
            document.getElementById('viewTitle').innerText = task.title;
            document.getElementById('viewDesc').innerText = task.description || 'No description provided.';
            document.getElementById('viewDueDate').innerText = task.dueDate;
            document.getElementById('viewPriorityBadge').innerText = task.priority + ' Priority';

            const statusBadge = document.getElementById('viewStatusBadge');
            statusBadge.innerText = isCompleted ? 'Completed' : 'Pending';
            statusBadge.className = isCompleted
                ? "text-xs font-medium px-2.5 py-1 rounded-md border border-emerald-200 dark:border-emerald-800 text-emerald-600 dark:text-emerald-400 bg-emerald-50 dark:bg-emerald-950/50"
                : "text-xs font-medium px-2.5 py-1 rounded-md border border-amber-200 dark:border-amber-800 text-amber-600 dark:text-amber-400 bg-amber-50 dark:bg-amber-950/50";

            document.getElementById('viewToggleIcon').className = `fa-solid text-xs ${isCompleted ? 'fa-rotate-left' : 'fa-check'}`;
            document.getElementById('viewToggleLabel').innerText = isCompleted ? 'Reopen Task' : 'Mark Complete';

            showModal();
        }

        // Open the empty form in Create mode (New Task button)
        function openCreateModal() {
            currentTaskId = null;
            document.getElementById('taskForm').reset();
            document.getElementById('taskId').value = '';
            document.getElementById('formTitle').innerText = 'Create New Task';
            document.getElementById('viewMode').classList.add('hidden');
            document.getElementById('formMode').classList.remove('hidden');
            showModal();
        }

        // Open the form pre-filled in Edit mode (Edit button inside View)
        function openEditModal(id) {
            const task = tasks.find(t => t.id === id);
            if (!task) return;

            document.getElementById('taskId').value = task.id;
            document.getElementById('taskTitle').value = task.title;
            document.getElementById('taskDesc').value = task.description;
            document.getElementById('taskPriority').value = task.priority;
            document.getElementById('taskDueDate').value = task.dueDate;
            document.getElementById('formTitle').innerText = 'Edit Task';

            document.getElementById('viewMode').classList.add('hidden');
            document.getElementById('formMode').classList.remove('hidden');
        }

        // Cancel out of the form: back to View if editing, else close entirely
        function handleFormCancel() {
            const id = document.getElementById('taskId').value;
            if (id) {
                openViewModal(id);
            } else {
                closeModal();
            }
        }

        // Form Submission handler (Create or Update)
        function handleFormSubmit(event) {
            event.preventDefault();
            const id = document.getElementById('taskId').value;
            const title = document.getElementById('taskTitle').value.trim();
            const description = document.getElementById('taskDesc').value.trim();
            const priority = document.getElementById('taskPriority').value;
            const dueDate = document.getElementById('taskDueDate').value;

            if (!title || !dueDate) return;

            if (id) {
                tasks = tasks.map(t => t.id === id ? { ...t, title, description, priority, dueDate } : t);
                showFlash('Task successfully updated!', 'success');
            } else {
                const newTask = {
                    id: Date.now().toString(),
                    title, description, priority, dueDate,
                    status: 'pending'
                };
                tasks.unshift(newTask);
                showFlash('New task created successfully!', 'success');
            }

            closeModal();
            renderApp();
        }

        // Toggle task completion status
        function toggleTaskStatus(id) {
            if (!id) return;
            tasks = tasks.map(t => {
                if (t.id === id) {
                    const newStatus = t.status === 'completed' ? 'pending' : 'completed';
                    showFlash(`Task marked as ${newStatus}!`, 'success');
                    return { ...t, status: newStatus };
                }
                return t;
            });
            closeModal();
            renderApp();
        }

        // Delete task
        function deleteTask(id) {
            if (!id) return;
            if (confirm('Are you sure you want to remove this task?')) {
                tasks = tasks.filter(t => t.id !== id);
                showFlash('Task deleted successfully.', 'error');
                closeModal();
                renderApp();
            }
        }

        // Flash message notification banner
        function showFlash(message, type) {
            const wrapper = document.getElementById('flashMessage');
            const text = document.getElementById('flashText');
            const icon = document.getElementById('flashIcon');

            text.innerText = message;
            icon.className = type === 'success'
                ? "fa-solid fa-circle-check text-xs text-emerald-600"
                : "fa-solid fa-circle-exclamation text-xs text-rose-600";

            wrapper.classList.remove('hidden');
            setTimeout(() => wrapper.classList.add('hidden'), 3000);
        }

        // HTML Sanitizer helper
        function escapeHtml(str) {
            return str.replace(/&/g, "&amp;").replace(/</g, "&lt;").replace(/>/g, "&gt;").replace(/"/g, "&quot;").replace(/'/g, "&#039;");
        }
    </script>
</body>
</html>
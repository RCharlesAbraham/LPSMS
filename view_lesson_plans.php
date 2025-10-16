<?php
require_once 'config.php';

try {
    // Fetch all lesson plans
    $stmt = $pdo->query("SELECT * FROM Lesson_Plan ORDER BY LessonPlan_ID DESC");
    $lessonPlans = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = $e->getMessage();
}
?>

<!DOCTYPE html>
<html lang="ms">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lesson Plans - School Management System</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f8fafc;
            color: #334155;
            margin: 0;
            padding: 20px;
            line-height: 1.6;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
        }
        .card {
            background: white;
            border-radius: 12px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .card-title {
            font-size: 20px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 24px 0;
        }
        .card-body {
            padding: 24px;
        }
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 12px;
        }
        .nav-tabs {
            display: flex;
            gap: 16px;
            align-items: center;
        }
        .nav-tab {
            color: #64748b;
            text-decoration: none;
            font-weight: 500;
            padding: 8px 0;
            border-bottom: 2px solid transparent;
            transition: all 0.2s;
        }
        .nav-tab.active {
            color: #3b82f6;
            border-bottom-color: #3b82f6;
        }
        .nav-tab:hover {
            color: #3b82f6;
        }
        .nav-separator {
            color: #e2e8f0;
        }
        .search-bar {
            display: flex;
            align-items: center;
            gap: 8px;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 8px 12px;
            min-width: 200px;
        }
        .search-input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #334155;
            width: 100%;
        }
        .search-input::placeholder {
            color: #94a3b8;
        }
        .toolbar-actions {
            display: flex;
            gap: 8px;
        }
        .btn-icon {
            width: 36px;
            height: 36px;
            border: none;
            background: #f1f5f9;
            border-radius: 8px;
            display: flex;
            align-items: center;
            justify-content: center;
            cursor: pointer;
            transition: all 0.2s;
        }
        .btn-icon:hover {
            background: #e2e8f0;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            background: #3b82f6;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
        }
        .btn:hover {
            background: #2563eb;
        }
        .btn-success {
            background: #10b981;
        }
        .btn-success:hover {
            background: #059669;
        }
        .btn-secondary {
            background: #f1f5f9;
            color: #64748b;
        }
        .btn-secondary:hover {
            background: #e2e8f0;
        }
        .btn-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            padding: 0;
            justify-content: center;
        }
        .btn-edit {
            background: #8b5cf6;
        }
        .btn-edit:hover {
            background: #7c3aed;
        }
        .btn-delete {
            background: #6b7280;
        }
        .btn-delete:hover {
            background: #4b5563;
        }
        .btn-view {
            background: #3b82f6;
        }
        .btn-view:hover {
            background: #2563eb;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: left;
            border-bottom: 1px solid #e2e8f0;
            font-size: 14px;
        }
        .data-table th {
            background: white;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e2e8f0;
        }
        .data-table tbody tr {
            transition: background-color 0.2s;
        }
        .data-table tbody tr:hover {
            background-color: #f8fafc;
        }
        .status-badge {
            display: inline-flex;
            align-items: center;
            padding: 4px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.yes {
            background: #d1fae5;
            color: #065f46;
        }
        .status-badge.no {
            background: #fee2e2;
            color: #991b1b;
        }
        .table-footer {
            padding: 16px 24px;
            background: #f8fafc;
            border-top: 1px solid #e2e8f0;
            font-size: 14px;
            color: #64748b;
        }
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #64748b;
        }
        .empty-state-icon {
            margin-bottom: 16px;
        }
        .error {
            background: #fef2f2;
            color: #dc2626;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #fecaca;
        }
        .modal {
            position: fixed;
            inset: 0;
            background: rgba(0, 0, 0, 0.5);
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 1000;
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 700px;
            width: 95%;
            max-height: 90vh;
            overflow: hidden;
        }
        .modal-header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e2e8f0;
        }
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 24px 0;
        }
        .modal-body {
            padding: 24px;
            max-height: 60vh;
            overflow-y: auto;
        }
        .modal-footer {
            padding: 0 24px 24px 24px;
            display: flex;
            justify-content: flex-end;
            gap: 12px;
        }
        .form-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 16px;
        }
        .form-field {
            display: flex;
            flex-direction: column;
        }
        .form-label {
            font-size: 14px;
            font-weight: 500;
            color: #374151;
            margin-bottom: 6px;
        }
        .form-input {
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #334155;
            transition: border-color 0.2s;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        @media (max-width: 768px) {
            .form-grid {
                grid-template-columns: 1fr;
            }
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-bar {
                min-width: auto;
            }
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">List Lesson Plans</h1>
                <div class="toolbar">
                    <div class="nav-tabs">
                        <a href="#" class="nav-tab active">All</a>
                        <span class="nav-separator">|</span>
                        <a href="#" class="nav-tab">Trashed</a>
                    </div>
                    <div class="search-bar">
                        <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                            <path d="M7.333 12.667A5.333 5.333 0 1 0 7.333 2a5.333 5.333 0 0 0 0 10.667ZM14 14l-2.9-2.9" stroke="#94a3b8" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <input type="text" class="search-input" placeholder="Search">
                    </div>
                    <div class="toolbar-actions">
                        <button class="btn-icon" title="Refresh">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M1.333 8a6.667 6.667 0 1 0 6.667-6.667v2.667" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M8 2.667L10.667 5.333 8 8" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                        <button class="btn-icon" title="Column Visibility">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M2.667 4h10.666v8H2.667z" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M6.667 4v8M9.333 4v8" stroke="#64748b" stroke-width="1.33"/>
                            </svg>
                        </button>
                        <button class="btn-icon" title="Export">
                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                <path d="M8 1.333v9.334" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M5.333 6.667L8 9.333l2.667-2.666" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                <path d="M1.333 14.667h13.334" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
            
            <div class="card-body">
                <a href="Untitled-1.html" class="btn btn-success" style="margin-bottom: 20px;">
                    <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                        <path d="M8 1.333v13.334" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                        <path d="M1.333 8h13.334" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                    </svg>
                    Add New Lesson Plan
                </a>
                
                <?php if (isset($error)): ?>
                    <div class="error">
                        <p>Error: <?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (empty($lessonPlans) && !isset($error)): ?>
                    <div class="empty-state">
                        <div class="empty-state-icon">
                            <svg width="48" height="48" viewBox="0 0 48 48" fill="none">
                                <path d="M24 4C12.954 4 4 12.954 4 24s8.954 20 20 20 20-8.954 20-20S35.046 4 24 4zm0 36c-8.837 0-16-7.163-16-16S15.163 8 24 8s16 7.163 16 16-7.163 16-16 16z" fill="#cbd5e1"/>
                                <path d="M24 16v16M16 24h16" stroke="#cbd5e1" stroke-width="2" stroke-linecap="round"/>
                            </svg>
                        </div>
                        <p>No lesson plans found. Click the button above to add your first lesson plan.</p>
                    </div>
                <?php else: ?>
                    <table class="data-table">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Cluster</th>
                                <th>Theme</th>
                                <th>Topic</th>
                                <th>Year</th>
                                <th>Duration</th>
                                <th>Method</th>
                                <th>Action</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php $index = 1; foreach ($lessonPlans as $plan): ?>
                            <tr>
                                <td><?php echo $index++; ?></td>
                                <td><?php echo htmlspecialchars($plan['CLUSTER']); ?></td>
                                <td><?php echo htmlspecialchars($plan['THEME']); ?></td>
                                <td><?php echo htmlspecialchars($plan['TOPIC']); ?></td>
                                <td><?php echo htmlspecialchars($plan['YEAR']); ?></td>
                                <td><?php echo htmlspecialchars($plan['DURATION (minutes)']); ?> min</td>
                                <td><?php echo htmlspecialchars($plan['METHOD']); ?></td>
                                <td>
                                    <div style="display: flex; gap: 8px;">
                                        <button class="btn btn-circle btn-view" data-action="view" data-id="<?php echo (int)$plan['LessonPlan_ID']; ?>" title="View">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M8 1.333c-3.314 0-6 2.686-6 6s2.686 6 6 6 6-2.686 6-6-2.686-6-6-6z" stroke="currentColor" stroke-width="1.33"/>
                                                <circle cx="8" cy="7.333" r="1.333" fill="currentColor"/>
                                            </svg>
                                        </button>
                                        <button class="btn btn-circle btn-edit" data-action="edit" data-id="<?php echo (int)$plan['LessonPlan_ID']; ?>" title="Edit">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M11.333 2a1.333 1.333 0 0 1 1.334 1.333L12.667 13.333a1.333 1.333 0 0 1-1.334 1.334H2.667a1.333 1.333 0 0 1-1.334-1.334V3.333A1.333 1.333 0 0 1 2.667 2h8.666z" stroke="currentColor" stroke-width="1.33"/>
                                                <path d="M5.333 6.667l2 2 4-4" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                        <button class="btn btn-circle btn-delete" data-action="delete" data-id="<?php echo (int)$plan['LessonPlan_ID']; ?>" title="Delete">
                                            <svg width="16" height="16" viewBox="0 0 16 16" fill="none">
                                                <path d="M2 4h12" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                                <path d="M5.333 4V2.667a1.333 1.333 0 0 1 1.334-1.334h2.666a1.333 1.333 0 0 1 1.334 1.334V4M6.667 7.333v4M9.333 7.333v4M12.667 4v9.333a1.333 1.333 0 0 1-1.334 1.334H4.667a1.333 1.333 0 0 1-1.334-1.334V4" stroke="currentColor" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                    
                    <div class="table-footer">
                        Showing 1 to <?php echo count($lessonPlans); ?> of <?php echo count($lessonPlans); ?> rows
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>

    <!-- View Modal -->
    <div id="viewModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">View Lesson Plan</h2>
            </div>
            <div class="modal-body">
                <div id="viewBody"></div>
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('viewModal')">Close</button>
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Lesson Plan</h2>
            </div>
            <div class="modal-body">
                <form id="editForm">
                    <input type="hidden" name="LessonPlan_ID" id="edit_LessonPlan_ID" />
                    <div class="form-grid">
                        <div class="form-field">
                            <label class="form-label">Cluster</label>
                            <input type="text" name="CLUSTER" id="edit_CLUSTER" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Theme</label>
                            <input type="text" name="THEME" id="edit_THEME" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Sub Theme</label>
                            <input type="text" name="SUB_THEME" id="edit_SUB_THEME" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Topic</label>
                            <input type="text" name="TOPIC" id="edit_TOPIC" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Year</label>
                            <input type="text" name="YEAR" id="edit_YEAR" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Duration (minutes)</label>
                            <input type="number" name="DURATION_minutes" id="edit_DURATION" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Instructional Design</label>
                            <input type="text" name="INSTRUCTIONAL_DESIGN" id="edit_INSTRUCTIONAL_DESIGN" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Technology Integration</label>
                            <input type="text" name="TECHNOLOGY_INTEGRATION" id="edit_TECHNOLOGY_INTEGRATION" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Approach</label>
                            <input type="text" name="APPROACH" id="edit_APPROACH" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Method</label>
                            <input type="text" name="METHOD" id="edit_METHOD" class="form-input" />
                        </div>
                        <div class="form-field">
                            <label class="form-label">Parental Involvement</label>
                            <input type="text" name="PARENTAL_INVOLVEMENT" id="edit_PARENTAL_INVOLVEMENT" class="form-input" />
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" onclick="closeModal('editModal')">Cancel</button>
                <button type="submit" form="editForm" class="btn btn-success">Save Changes</button>
            </div>
        </div>
    </div>

    <!-- Delete Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title">Delete Lesson Plan</h3>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this lesson plan?</p>
                <input type="hidden" id="delete_id" />
            </div>
            <div class="modal-footer">
                <button class="btn btn-secondary" onclick="closeModal('deleteModal')">Cancel</button>
                <button class="btn btn-danger" id="confirmDeleteBtn">Delete</button>
            </div>
        </div>
    </div>

    <script>
        function openModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.display = 'flex';
        }
        function closeModal(id) {
            const el = document.getElementById(id);
            if (!el) return;
            el.style.display = 'none';
        }

        document.addEventListener('click', async (e) => {
            const btn = e.target.closest('button[data-action]');
            if (!btn) return;
            const id = btn.getAttribute('data-id');
            const action = btn.getAttribute('data-action');

            if (action === 'view' || action === 'edit') {
                const res = await fetch(`lesson_plan_api.php?action=get&id=${id}`);
                const data = await res.json();
                if (!data.success) { alert(data.error || 'Failed to load'); return; }
                const p = data.data;
                if (action === 'view') {
                    const html = `
                        <table class="data-table">
                            <tr><th>ID</th><td>${p.LessonPlan_ID}</td></tr>
                            <tr><th>Cluster</th><td>${p.CLUSTER || ''}</td></tr>
                            <tr><th>Theme</th><td>${p.THEME || ''}</td></tr>
                            <tr><th>Sub Theme</th><td>${p.SUB_THEME || ''}</td></tr>
                            <tr><th>Topic</th><td>${p.TOPIC || ''}</td></tr>
                            <tr><th>Year</th><td>${p.YEAR || ''}</td></tr>
                            <tr><th>Duration (minutes)</th><td>${p['DURATION (minutes)'] || ''}</td></tr>
                            <tr><th>Instructional Design</th><td>${p['INSTRUCTIONAL DESIGN'] || ''}</td></tr>
                            <tr><th>Technology Integration</th><td>${p['TECHNOLOGY INTEGRATION'] || ''}</td></tr>
                            <tr><th>Approach</th><td>${p.APPROACH || ''}</td></tr>
                            <tr><th>Method</th><td>${p.METHOD || ''}</td></tr>
                            <tr><th>Parental Involvement</th><td>${p['PARENTAL INVOLVEMENT'] || ''}</td></tr>
                        </table>`;
                    document.getElementById('viewBody').innerHTML = html;
                    openModal('viewModal');
                } else {
                    document.getElementById('edit_LessonPlan_ID').value = p.LessonPlan_ID;
                    document.getElementById('edit_CLUSTER').value = p.CLUSTER || '';
                    document.getElementById('edit_THEME').value = p.THEME || '';
                    document.getElementById('edit_SUB_THEME').value = p.SUB_THEME || '';
                    document.getElementById('edit_TOPIC').value = p.TOPIC || '';
                    document.getElementById('edit_YEAR').value = p.YEAR || '';
                    document.getElementById('edit_DURATION').value = p['DURATION (minutes)'] || '';
                    document.getElementById('edit_INSTRUCTIONAL_DESIGN').value = p['INSTRUCTIONAL DESIGN'] || '';
                    document.getElementById('edit_TECHNOLOGY_INTEGRATION').value = p['TECHNOLOGY INTEGRATION'] || '';
                    document.getElementById('edit_APPROACH').value = p.APPROACH || '';
                    document.getElementById('edit_METHOD').value = p.METHOD || '';
                    document.getElementById('edit_PARENTAL_INVOLVEMENT').value = p['PARENTAL INVOLVEMENT'] || '';
                    openModal('editModal');
                }
            }

            if (action === 'delete') {
                document.getElementById('delete_id').value = id;
                openModal('deleteModal');
            }
        });

        document.getElementById('editForm').addEventListener('submit', async (e) => {
            e.preventDefault();
            const form = e.target;
            const formData = new FormData(form);
            formData.append('action', 'update');
            const res = await fetch('lesson_plan_api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (!data.success) { alert(data.error || 'Update failed'); return; }
            closeModal('editModal');
            location.reload();
        });

        document.getElementById('confirmDeleteBtn').addEventListener('click', async () => {
            const id = document.getElementById('delete_id').value;
            const formData = new FormData();
            formData.append('action', 'delete');
            formData.append('LessonPlan_ID', id);
            const res = await fetch('lesson_plan_api.php', { method: 'POST', body: formData });
            const data = await res.json();
            if (!data.success) { alert(data.error || 'Delete failed'); return; }
            closeModal('deleteModal');
            location.reload();
        });
    </script>
</body>
</html>
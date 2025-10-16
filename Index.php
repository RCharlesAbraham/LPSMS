<?php
require_once 'config.php';

try {
    // Fetch all lesson plans
    $stmt = $pdo->query("SELECT * FROM Lesson_Plan ORDER BY LessonPlan_ID DESC");
    $lessonPlans = $stmt->fetchAll();
} catch (PDOException $e) {
    $error = "Database error: " . $e->getMessage();
    $lessonPlans = []; // Ensure $lessonPlans is an array
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <title>Lesson Plan Form</title>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
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
            max-width: 100%;
            margin: 0 auto;
            width: 100%;
        }
        .form-container {
            max-width: 100%;
            width: 100%;
            margin: 0;
            padding: 20px;
        }
        .form-container .card {
            margin-bottom: 0;
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
        .form-row {
            display: flex;
            flex-wrap: wrap;
            margin: 0 -12px 20px -12px;
        }
        .form-column {
            flex: 0 0 25%;
            max-width: 25%;
            padding: 0 12px;
        }
        .form-column-wide {
            flex: 0 0 50%;
            max-width: 50%;
            padding: 0 12px;
        }
        .form-column-narrow {
            flex: 0 0 16.666667%;
            max-width: 16.666667%;
            padding: 0 12px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            margin-bottom: 6px;
            font-weight: 500;
            color: #374151;
            font-size: 14px;
        }
        .form-group label .required {
            color: #dc2626;
            margin-left: 3px;
        }
        .form-input {
            width: 100%;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #334155;
            transition: border-color 0.2s;
            background: white;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-input::placeholder {
            color: #94a3b8;
        }
        .section-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 24px 0 16px 0;
            padding-bottom: 8px;
            border-bottom: 1px solid #e2e8f0;
        }
        .checkbox-container {
            margin-bottom: 24px;
        }
        .checkbox-group {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 12px;
            margin-top: 12px;
        }
        .checkbox-group-vertical {
            display: flex;
            flex-direction: column;
            gap: 8px;
            margin-top: 12px;
        }
        .text-area {
            width: 100%;
            min-height: 100px;
            padding: 8px 12px;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            font-size: 14px;
            color: #334155;
            font-family: inherit;
            resize: vertical;
            transition: border-color 0.2s;
        }
        .text-area:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .standards-list {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
            margin-top: 12px;
            max-height: 300px;
            overflow-y: auto;
        }
        .standards-list ol {
            margin: 0;
            padding-left: 20px;
        }
        .standards-list li {
            margin-bottom: 8px;
            font-size: 13px;
            line-height: 1.4;
            color: #374151;
        }
        .section-grid {
            display: grid;
            grid-template-columns: 2fr 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .section-grid-2 {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .section-grid-3 {
            display: grid;
            grid-template-columns: 1fr 1fr 1fr;
            gap: 20px;
            margin-top: 20px;
        }
        .section-block {
            background: #f8fafc;
            border: 1px solid #e2e8f0;
            border-radius: 8px;
            padding: 16px;
        }
        .section-block h4 {
            margin: 0 0 12px 0;
            font-size: 14px;
            font-weight: 600;
            color: #1e293b;
        }
        .checkbox-option {
            display: flex;
            align-items: center;
            padding: 8px 0;
        }
        .checkbox-option input[type="checkbox"],
        .checkbox-option input[type="radio"] {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            accent-color: #3b82f6;
        }
        .checkbox-option label {
            margin: 0;
            font-weight: 400;
            color: #374151;
            cursor: pointer;
        }
        .divider {
            height: 1px;
            background-color: #e2e8f0;
            margin: 24px 0;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        .btn {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 16px;
            border-radius: 8px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 14px;
            transition: all 0.2s;
            text-decoration: none;
        }
        .btn-reset {
            background: #f1f5f9;
            color: #64748b;
        }
        .btn-reset:hover {
            background: #e2e8f0;
        }
        .btn-submit {
            background: #3b82f6;
            color: white;
        }
        .btn-submit:hover {
            background: #2563eb;
        }

        /* Styles for the view table */
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
        .table-responsive {
            overflow-x: auto;
        }
        .btn-circle {
            width: 32px;
            height: 32px;
            border-radius: 50%;
            padding: 0;
            justify-content: center;
            margin: 0 4px;
        }
        .btn-edit {
            /* Font & Text */
            font-family: ubuntu-bold, sans-serif;
            font-size: 10px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 47px;
            text-decoration: rgb(255, 255, 255);
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: middle;
            white-space: normal;
            word-spacing: 0px;
            
            /* Color & Background */
            background-attachment: scroll;
            background-color: rgba(0, 0, 0, 0);
            background-image: linear-gradient(to right, rgb(218, 140, 255), #7c3aed);
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            
            /* Box */
            height: 41.9886px;
            width: 41.9886px;
            border: 0px none rgb(255, 255, 255);
            border-top: 0px none rgb(255, 255, 255);
            border-right: 0px none rgb(255, 255, 255);
            border-bottom: 0px none rgb(255, 255, 255);
            border-left: 0px none rgb(255, 255, 255);
            margin: 0px;
            padding: 0px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            /* Positioning */
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: inline-block;
            clear: none;
            z-index: auto;
            
            /* List */
            list-style-image: none;
            list-style-type: disc;
            list-style-position: outside;
            
            /* Table */
            border-collapse: collapse;
            border-spacing: 1.81818px;
            caption-side: top;
            empty-cells: show;
            table-layout: auto;
            
            /* Miscellaneous */
            overflow: visible;
            cursor: pointer;
            visibility: visible;
            
            /* Effects */
            transform: none;
            transition: opacity 0.3s;
            outline: none;
            outline-offset: 0px;
            box-sizing: border-box;
            resize: none;
            text-shadow: none;
            text-overflow: clip;
            word-wrap: break-word;
            box-shadow: none;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .btn-edit:hover {
            background-image: linear-gradient(to right, rgb(200, 120, 255), #6d28d9);
            color: rgb(255, 255, 255);
        }
        
        /* FontAwesome Icon Styles */
        .icon {
            /* Font & Text */
            font-family: FontAwesome;
            font-size: 16px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 16px;
            text-decoration: rgb(255, 255, 255);
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: baseline;
            white-space: normal;
            word-spacing: 0px;
            
            /* Color & Background */
            background-attachment: scroll;
            background-color: rgba(0, 0, 0, 0);
            background-image: none;
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            
            /* Box */
            height: 15.9943px;
            width: 16.0085px;
            border: 0px none rgb(255, 255, 255);
            margin: 0px;
            padding: 0px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            /* Positioning */
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: inline-block;
            clear: none;
            z-index: auto;
            
            /* Miscellaneous */
            overflow: visible;
            cursor: pointer;
            visibility: visible;
            
            /* Effects */
            transform: none;
            transition: all;
            outline: none;
            outline-offset: 0px;
            box-sizing: border-box;
            resize: none;
            text-shadow: none;
            text-overflow: clip;
            word-wrap: break-word;
            box-shadow: none;
            border-radius: 0px;
        }
        
        .icon-edit::before {
            content: "\f044";
        }
        
        .icon-trash::before {
            content: "\f1f8";
        }
        
        .icon-eye::before {
            content: "\f06e";
        }
        .btn-delete {
            /* Font & Text */
            font-family: ubuntu-bold, sans-serif;
            font-size: 10px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 47px;
            text-decoration: rgb(255, 255, 255);
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: middle;
            white-space: normal;
            word-spacing: 0px;
            
            /* Color & Background */
            background-attachment: scroll;
            background-color: rgba(0, 0, 0, 0);
            background-image: linear-gradient(89deg, rgb(94, 113, 136), rgb(62, 75, 91));
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            
            /* Box */
            height: 41.9886px;
            width: 41.9886px;
            border: 0px none rgb(255, 255, 255);
            border-top: 0px none rgb(255, 255, 255);
            border-right: 0px none rgb(255, 255, 255);
            border-bottom: 0px none rgb(255, 255, 255);
            border-left: 0px none rgb(255, 255, 255);
            margin: 0px;
            padding: 0px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            /* Positioning */
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: inline-block;
            clear: none;
            z-index: auto;
            
            /* List */
            list-style-image: none;
            list-style-type: disc;
            list-style-position: outside;
            
            /* Table */
            border-collapse: collapse;
            border-spacing: 1.81818px;
            caption-side: top;
            empty-cells: show;
            table-layout: auto;
            
            /* Miscellaneous */
            overflow: visible;
            cursor: pointer;
            visibility: visible;
            
            /* Effects */
            transform: none;
            transition: opacity 0.3s;
            outline: none;
            outline-offset: 0px;
            box-sizing: border-box;
            resize: none;
            text-shadow: none;
            text-overflow: clip;
            word-wrap: break-word;
            box-shadow: none;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .btn-delete:hover {
            background-image: linear-gradient(89deg, rgb(84, 103, 126), rgb(52, 65, 81));
            color: rgb(255, 255, 255);
        }
        .btn-danger {
            background: #dc2626;
        }
        .btn-danger:hover {
            background: #b91c1c;
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
        .btn-view {
            /* Font & Text */
            font-family: ubuntu-bold, sans-serif;
            font-size: 10px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 47px;
            text-decoration: rgb(255, 255, 255);
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: middle;
            white-space: normal;
            word-spacing: 0px;
            
            /* Color & Background */
            background-attachment: scroll;
            background-color: rgba(0, 0, 0, 0);
            background-image: linear-gradient(to right, rgb(132, 217, 210), rgb(7, 205, 174));
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            
            /* Box */
            height: 41.9886px;
            width: 41.9886px;
            border: 0px none rgb(255, 255, 255);
            border-top: 0px none rgb(255, 255, 255);
            border-right: 0px none rgb(255, 255, 255);
            border-bottom: 0px none rgb(255, 255, 255);
            border-left: 0px none rgb(255, 255, 255);
            margin: 0px;
            padding: 0px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            /* Positioning */
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: inline-block;
            clear: none;
            z-index: auto;
            
            /* List */
            list-style-image: none;
            list-style-type: disc;
            list-style-position: outside;
            
            /* Table */
            border-collapse: collapse;
            border-spacing: 1.81818px;
            caption-side: top;
            empty-cells: show;
            table-layout: auto;
            
            /* Miscellaneous */
            overflow: visible;
            cursor: pointer;
            visibility: visible;
            
            /* Effects */
            transform: none;
            transition: opacity 0.3s;
            outline: none;
            outline-offset: 0px;
            box-sizing: border-box;
            resize: none;
            text-shadow: none;
            text-overflow: clip;
            word-wrap: break-word;
            box-shadow: none;
            border-top-left-radius: 50px;
            border-top-right-radius: 50px;
            border-bottom-left-radius: 50px;
            border-bottom-right-radius: 50px;
        }
        .btn-view:hover {
            background-image: linear-gradient(to right, rgb(122, 207, 200), rgb(0, 195, 164));
            color: rgb(255, 255, 255);
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
            transition: opacity 0.3s ease;
        }
        .success {
            background: #f0fdf4;
            color: #166534;
            padding: 16px;
            border-radius: 8px;
            margin-bottom: 20px;
            border: 1px solid #bbf7d0;
            transition: opacity 0.3s ease;
        }

        /* Modal Styles */
        .modal {
            display: none; 
            position: fixed; 
            z-index: 1000; 
            left: 0;
            top: 0;
            width: 100%; 
            height: 100%; 
            overflow: auto; 
            background-color: rgba(0,0,0,0.5);
        }
        .modal-content {
            background: white;
            border-radius: 12px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 700px;
            width: 95%;
            max-height: 90vh;
            overflow: hidden;
            margin: 5% auto;
        }
        .modal-header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e2e8f0;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-size: 18px;
            font-weight: 600;
            color: #1e293b;
            margin: 0 0 24px 0;
        }
        .close-btn {
            color: #94a3b8;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }
        .close-btn:hover {
            color: #64748b;
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
        @media (max-width: 768px) {
            .form-column, .form-column-wide, .form-column-narrow {
                flex: 0 0 100%;
                max-width: 100%;
            }
            .section-grid, .section-grid-2, .section-grid-3 {
                grid-template-columns: 1fr;
                gap: 16px;
            }
            .toolbar {
                flex-direction: column;
                align-items: stretch;
            }
            .search-bar {
                min-width: auto;
            }
            .container {
                max-width: 100%;
                padding: 10px;
                width: 100%;
            }
        }
    </style>
</head>
<body>
    <div class="form-container">
        <div class="card">
            <div class="card-header">
                <h1 class="card-title">Create Lesson Plan</h1>
            </div>
            <div class="card-body">
                <form method="POST" action="add_lesson_plan.php">
                    <!-- General Information Section -->
                    <div class="form-row">
                        <!-- Column 1 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>KLUSTER <span class="required">*</span></label>
                                <input type="text" name="CLUSTER" class="form-input" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>TEMA <span class="required">*</span></label>
                                <input type="text" name="THEME" class="form-input" placeholder="" required>
                            </div>
                        </div>
                        <!-- Column 2 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>SUB-TEMA <span class="required">*</span></label>
                                <input type="text" name="SUB_THEME" class="form-input" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>TOPIK <span class="required">*</span></label>
                                <input type="text" name="TOPIC" class="form-input" placeholder="" required>
                            </div>
                        </div>
                        <!-- Column 3 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>TAHUN <span class="required">*</span></label>
                                <select name="YEAR" class="form-input" required>
                                    <option value="">Select Year</option>
                                    <option value="SATU">SATU</option>
                                    <option value="DUA">DUA</option>
                                    <option value="TIGA">TIGA</option>
                                    <option value="EMPAT">EMPAT</option>
                                    <option value="LIMA">LIMA</option>
                                    <option value="ENAM">ENAM</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>DURASI PELAKSANAAN (minit) <span class="required">*</span></label>
                                <input type="text" name="DURATION" class="form-input" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <!-- Main Content Grid -->
                    <div class="section-grid">
                        <!-- Learning Standards Section -->
                        <div class="section-block">
                            <h4>STANDARD PEMBELAJARAN (Learning Standards)</h4>
                            <div class="form-group">
                                <label>PENDIDIKAN ISLAM</label>
                                <div class="standards-list">
                                    <ol>
                                        <li>Murid dapat menyatakan keistimewaan Rasulullah SAW melalui keturunan, peristiwa kelahiran dan ibrah yang boleh diambil. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat menceritakan peristiwa kelahiran Rasulullah SAW dengan betul. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat menerangkan ibrah yang boleh diambil daripada peristiwa kelahiran Rasulullah SAW. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat mengaplikasikan nilai-nilai murni daripada keistimewaan Rasulullah SAW dalam kehidupan seharian. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                    </ol>
                                    <ol>
                                        <li>Murid dapat mengenal pasti sifat Siddiq Rasulullah SAW. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat menceritakan contoh-contoh sifat Siddiq Rasulullah SAW. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat menerangkan kepentingan sifat Siddiq dalam kehidupan. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat mengaplikasikan sifat Siddiq dalam kehidupan seharian. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                    </ol>
                                    <ol>
                                        <li>Murid dapat mengenal pasti contoh-contoh sifat Siddiq Rasulullah SAW dalam kehidupan seharian. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                        <li>Murid dapat mengaplikasikan sifat Siddiq Rasulullah SAW dalam kehidupan seharian. (KPI5, DKM4, DKM7, DKM8, DKM9, DKM10, DKM13, KI1, KI2, KI4, KI7)</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Performance Standards Section -->
                        <div class="section-block">
                            <h4>STANDARD PRESTASI (Performance Standards)</h4>
                            <div class="form-group">
                                <label>Codes:</label>
                                <div class="checkbox-group-vertical">
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="PERFORMANCE_STANDARDS[]" value="PI125"> PI125
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="PERFORMANCE_STANDARDS[]" value="PI126"> PI126
                                    </label>
                                    <label class="checkbox-option">
                                        <input type="checkbox" name="PERFORMANCE_STANDARDS[]" value="PI127"> PI127
                                    </label>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Assessment Section -->
                        <div class="section-block">
                            <h4>PENTAKSIRAN (Assessment)</h4>
                            <div class="form-group">
                                <label>Assessment Methods:</label>
                                <div class="standards-list">
                                    <ol>
                                        <li>Penilaian melalui penglibatan murid dalam aktiviti berkumpulan</li>
                                        <li>Penilaian murid melalui aktiviti simulasi</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Second Row Grid -->
                    <div class="section-grid-3">
                        <!-- Instructional Design Section -->
                        <div class="section-block">
                            <h4>REKA BENTUK INSTRUKSI (Instructional Design)</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="INSTRUCTIONAL_DESIGN[]" value="Active Learning"> Active Learning
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="INSTRUCTIONAL_DESIGN[]" value="Constructive Learning"> Constructive Learning
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="INSTRUCTIONAL_DESIGN[]" value="Goal-Directed Learning"> Goal-Directed Learning
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="INSTRUCTIONAL_DESIGN[]" value="Collaborative Learning" checked> Collaborative Learning
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="INSTRUCTIONAL_DESIGN[]" value="Authentic Learning"> Authentic Learning
                                </label>
                            </div>
                            <small style="color: #64748b; font-size: 12px;">*Rujuk Technology Integration Matrix</small>
                        </div>
                        
                        <!-- Technology Integration Section -->
                        <div class="section-block">
                            <h4>INTEGRASI TEKNOLOGI (Technology Integration)</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="TECHNOLOGY_INTEGRATION[]" value="Entry Level"> Entry Level
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="TECHNOLOGY_INTEGRATION[]" value="Adoption Level" checked> Adoption Level
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="TECHNOLOGY_INTEGRATION[]" value="Adaptation Level"> Adaptation Level
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="TECHNOLOGY_INTEGRATION[]" value="Infusion Level"> Infusion Level
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="TECHNOLOGY_INTEGRATION[]" value="Transformation Level"> Transformation Level
                                </label>
                            </div>
                            <small style="color: #64748b; font-size: 12px;">*Rujuk Technology Integration Matrix</small>
                        </div>
                        
                        <!-- Approach Section -->
                        <div class="section-block">
                            <h4>PENDEKATAN (Approach)</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Inkuiri"> Inkuiri (Inquiry)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Pembelajaran Masteri"> Pembelajaran Masteri (Mastery Learning)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Berasaskan Masalah" checked> Berasaskan Masalah (Problem-Based)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Kontekstual" checked> Kontekstual (Contextual)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Berasaskan Projek"> Berasaskan Projek (Project-Based)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Berasaskan Pengalaman"> Berasaskan Pengalaman (Experience-Based)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Row Grid -->
                    <div class="section-grid-2">
                        <!-- Method Section -->
                        <div class="section-block">
                            <h4>KAEDAH (Method)</h4>
                            <div class="checkbox-group">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Simulasi" checked> Simulasi (Simulation)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Eksperimentasi"> Eksperimentasi (Experimentation)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Main Peranan" checked> Main Peranan (Role Play)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Nyanyian"> Nyanyian (Singing)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Bercerita"> Bercerita (Storytelling)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Tunjuk Cara"> Tunjuk Cara (Demonstration)
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Sumbangsaran"> Sumbangsaran (Brainstorming)
                                </label>
                            </div>
                            <div class="form-group" style="margin-top: 12px;">
                                <label>Lain-lain (Other):</label>
                                <input type="text" name="METHOD_OTHER" class="form-input" placeholder="e.g., Bermain (Playing)">
                            </div>
                        </div>
                        
                        <!-- Parental Involvement Section -->
                        <div class="section-block">
                            <h4>PENGLIBATAN IBU BAPA (Parental Involvement)</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="radio" name="PARENTAL_INVOLVEMENT" value="YA"> YA (Yes)
                                </label>
                                <label class="checkbox-option">
                                    <input type="radio" name="PARENTAL_INVOLVEMENT" value="TIDAK" checked> TIDAK (No)
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <!-- Performance Objectives and Activities -->
                    <div class="section-grid-2">
                        <!-- Performance Objectives Section -->
                        <div class="section-block">
                            <h4>OBJEKTIF PRESTASI (Performance Objectives)</h4>
                            <div class="form-group">
                                <label>Objectives:</label>
                                <div class="standards-list">
                                    <ol>
                                        <li>Merealisasikan keistimewaan Rasulullah menerusi faktor keturunan, peristiwa dan ibrah kelahiran Nabi Muhammad SAW. (P2)</li>
                                        <li>Mencontohi keperibadian unggul menerusi sifat Siddiq Nabi Muhammad SAW. (C4)</li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Activities Section -->
                        <div class="section-block">
                            <h4>AKTIVITI (Activities)</h4>
                            <div class="form-group">
                                <label>Activity Details:</label>
                                <div class="standards-list">
                                    <ol>
                                        <li><strong>Aktiviti menampal nama keluarga rasulullah saw di carta salasilah</strong>
                                            <ul style="margin-top: 4px; padding-left: 16px;">
                                                <li>Murid menampal nama keluarga Rasulullah SAW</li>
                                                <li>Murid mengenal pasti hubungan kekeluargaan</li>
                                            </ul>
                                        </li>
                                        <li><strong>Aktiviti simulasi</strong>
                                            <ul style="margin-top: 4px; padding-left: 16px;">
                                                <li>Murid melakonkan peristiwa kelahiran Rasulullah SAW</li>
                                                <li>Murid menerangkan ibrah yang boleh diambil</li>
                                            </ul>
                                        </li>
                                        <li><strong>Aktiviti simulasi</strong>
                                            <ul style="margin-top: 4px; padding-left: 16px;">
                                                <li>Murid melakonkan contoh sifat Siddiq Rasulullah SAW</li>
                                                <li>Murid mengaplikasikan sifat Siddiq dalam kehidupan</li>
                                            </ul>
                                        </li>
                                    </ol>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Teaching Aids Section -->
                    <div class="section-block">
                        <h4>ALAT BANTU MENGAJAR (Teaching Aids)</h4>
                        <div class="form-group">
                            <label>Teaching Materials:</label>
                            <textarea name="TEACHING_AIDS" class="text-area" placeholder="e.g., Sticky notes berwarna, kad manila, pita pelekat, papan putih, bahan-bahan untuk simulasi">Sticky notes berwarna, kad manila, pita pelekat, papan putih, bahan-bahan untuk simulasi</textarea>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="reset" class="btn btn-reset">Reset</button>
                        <button type="submit" class="btn btn-submit">Submit</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

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
                <?php if (isset($error)): ?>
                    <div class="error">
                        <p><?php echo htmlspecialchars($error); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['success'])): ?>
                    <div class="success">
                        <p><?php echo htmlspecialchars($_GET['success']); ?></p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($_GET['error'])): ?>
                    <div class="error">
                        <p><?php echo htmlspecialchars($_GET['error']); ?></p>
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
                        <p>No lesson plans found. Use the form above to add one.</p>
                    </div>
                <?php else: ?>
                    <div class="table-responsive">
                        <table class="data-table">
                            <thead>
                                <tr>
                                    <th>No.</th>
                                    <th>Name</th>
                                    <th>Start Month</th>
                                    <th>End Month</th>
                                    <th>Current</th>
                                    <th>Action</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; foreach ($lessonPlans as $plan): ?>
                                <tr>
                                    <td><?php echo $index++; ?></td>
                                    <td><?php echo htmlspecialchars($plan['CLUSTER']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['THEME']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['YEAR']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo (rand(0,1) ? 'yes' : 'no'); ?>">
                                            <?php echo (rand(0,1) ? 'Yes' : 'No'); ?>
                                        </span>
                                    </td>
                                    <td>
                                        <div style="display: flex; gap: 4px;">
                                            <button class="btn btn-circle btn-view view-btn" data-plan='<?php echo htmlspecialchars(json_encode($plan), ENT_QUOTES, 'UTF-8'); ?>' title="View">
                                                <i class="icon icon-eye"></i>
                                            </button>
                                            <button class="btn btn-circle btn-edit edit-btn" data-plan='<?php echo htmlspecialchars(json_encode($plan), ENT_QUOTES, 'UTF-8'); ?>' title="Edit">
                                                <i class="icon icon-edit"></i>
                                            </button>
                                            <button class="btn btn-circle btn-delete delete-btn" data-id="<?php echo $plan['LessonPlan_ID']; ?>" title="Delete">
                                                <i class="icon icon-trash"></i>
                                            </button>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
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
                <h2 class="modal-title">Lesson Plan Details</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Details will be populated by JavaScript -->
            </div>
        </div>
    </div>

    <!-- Edit Modal -->
    <div id="editModal" class="modal">
        <div class="modal-content">
            <div class="modal-header">
                <h2 class="modal-title">Edit Lesson Plan</h2>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <!-- Edit form will be here, populated by JS -->
                <!-- This would be a more complex form similar to your add form -->
                <p>Edit functionality requires a dedicated edit form. This is a placeholder.</p>
                <p>To implement this fully, you would create a form here and use JavaScript to populate its fields. The form would then submit to an `update_plan.php` script.</p>
            </div>
        </div>
    </div>

    <!-- Delete Confirmation Modal -->
    <div id="deleteModal" class="modal">
        <div class="modal-content" style="max-width: 450px;">
            <div class="modal-header">
                <h3 class="modal-title">Confirm Deletion</h3>
                <button class="close-btn">&times;</button>
            </div>
            <div class="modal-body">
                <p>Are you sure you want to delete this lesson plan?</p>
            </div>
            <div class="modal-footer">
                <button class="btn btn-reset" id="cancelDelete">Cancel</button>
                <a href="#" id="confirmDelete" class="btn btn-danger">Delete</a>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    const modals = [viewModal, editModal, deleteModal];

    // Auto-hide success/error messages after 5 seconds
    const successMessage = document.querySelector('.success');
    const errorMessage = document.querySelector('.error');
    
    if (successMessage) {
        setTimeout(() => {
            successMessage.style.opacity = '0';
            setTimeout(() => successMessage.remove(), 300);
        }, 5000);
    }
    
    if (errorMessage) {
        setTimeout(() => {
            errorMessage.style.opacity = '0';
            setTimeout(() => errorMessage.remove(), 300);
        }, 5000);
    }

    // Function to open a modal
    function openModal(modal) {
        modal.style.display = 'block';
    }

    // Function to close a modal
    function closeModal(modal) {
        modal.style.display = 'none';
    }

    // Close modal when clicking on 'x', the overlay, or cancel button
    modals.forEach(modal => {
        modal.querySelector('.close-btn').addEventListener('click', () => closeModal(modal));
    });
    window.addEventListener('click', (event) => {
        modals.forEach(modal => {
            if (event.target == modal) {
                closeModal(modal);
            }
        });
    });
    document.getElementById('cancelDelete').addEventListener('click', () => closeModal(deleteModal));

    // Event delegation for action buttons
    const tableContainer = document.querySelector('.table-responsive');
    if (tableContainer) {
        tableContainer.addEventListener('click', function(e) {
        // --- VIEW BUTTON ---
        if (e.target.closest('.view-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.view-btn');
            const planData = JSON.parse(btn.getAttribute('data-plan'));
            const viewBody = viewModal.querySelector('.modal-body');
            
            let html = '<ul>';
            for (const key in planData) {
                html += `<li><strong>${key.replace(/_/g, ' ').replace('(minutes)', ' (minutes)')}:</strong> ${planData[key] || 'N/A'}</li>`;
            }
            html += '</ul>';
            
            viewBody.innerHTML = html;
            openModal(viewModal);
        }

        // --- EDIT BUTTON ---
        if (e.target.closest('.edit-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.edit-btn');
            const planData = JSON.parse(btn.getAttribute('data-plan'));
            const editBody = editModal.querySelector('.modal-body');

            // Create a comprehensive edit form
            editBody.innerHTML = `
                <form action="update_plan.php" method="POST">
                    <input type="hidden" name="LessonPlan_ID" value="${planData.LessonPlan_ID}">
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label>KLUSTER <span class="required">*</span></label>
                                <input type="text" name="CLUSTER" class="form-input" value="${planData.CLUSTER || ''}" required>
                            </div>
                            <div class="form-group">
                                <label>TEMA <span class="required">*</span></label>
                                <input type="text" name="THEME" class="form-input" value="${planData.THEME || ''}" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label>SUB-TEMA <span class="required">*</span></label>
                                <input type="text" name="SUB_THEME" class="form-input" value="${planData.SUB_THEME || ''}" required>
                            </div>
                            <div class="form-group">
                                <label>TOPIK <span class="required">*</span></label>
                                <input type="text" name="TOPIC" class="form-input" value="${planData.TOPIC || ''}" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label>TAHUN <span class="required">*</span></label>
                                <select name="YEAR" class="form-input" required>
                                    <option value="">Select Year</option>
                                    <option value="SATU" ${planData.YEAR === 'SATU' ? 'selected' : ''}>SATU</option>
                                    <option value="DUA" ${planData.YEAR === 'DUA' ? 'selected' : ''}>DUA</option>
                                    <option value="TIGA" ${planData.YEAR === 'TIGA' ? 'selected' : ''}>TIGA</option>
                                    <option value="EMPAT" ${planData.YEAR === 'EMPAT' ? 'selected' : ''}>EMPAT</option>
                                    <option value="LIMA" ${planData.YEAR === 'LIMA' ? 'selected' : ''}>LIMA</option>
                                    <option value="ENAM" ${planData.YEAR === 'ENAM' ? 'selected' : ''}>ENAM</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>DURASI PELAKSANAAN (minit) <span class="required">*</span></label>
                                <input type="text" name="DURATION" class="form-input" value="${planData.DURATION || ''}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="form-group">
                        <label>ALAT BANTU MENGAJAR (Teaching Aids)</label>
                        <textarea name="TEACHING_AIDS" class="text-area" placeholder="e.g., Sticky notes berwarna, kad manila, pita pelekat, papan putih, bahan-bahan untuk simulasi">${planData.TEACHING_AIDS || ''}</textarea>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="btn btn-reset" onclick="closeModal(editModal)">Cancel</button>
                        <button type="submit" class="btn btn-submit">Save Changes</button>
                    </div>
                </form>
            `;
            openModal(editModal);
        }

        // --- DELETE BUTTON ---
        if (e.target.closest('.delete-btn')) {
            e.preventDefault();
            const btn = e.target.closest('.delete-btn');
            const planId = btn.getAttribute('data-id');
            const confirmDeleteLink = document.getElementById('confirmDelete');
            confirmDeleteLink.href = `delete_plan.php?id=${planId}`;
            openModal(deleteModal);
        }
        });
    }
});
</script>

</body>
</html>

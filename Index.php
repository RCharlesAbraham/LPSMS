<?php
require_once 'config.php';

// Generate CSRF token for form submission
session_start();
if (!isset($_SESSION['csrf_token'])) {
    $_SESSION['csrf_token'] = bin2hex(random_bytes(32));
}

try {
    // Fetch all lesson plans
    $stmt = $pdo->query("SELECT * FROM lesson_plan ORDER BY LessonPlan_ID DESC");
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
    <link href="https://fonts.googleapis.com/css2?family=Ubuntu:wght@500&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        * {
            box-sizing: border-box;
        }
        body {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
            background: #f3f4f6;
            color: #374151;
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
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1), 0 1px 2px rgba(0, 0, 0, 0.06);
            margin-bottom: 24px;
            overflow: hidden;
        }
        .card-header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e5e7eb;
        }
        .card-title {
            font-family: 'Ubuntu', sans-serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 21.6px;
            color: rgb(52, 58, 64);
            text-transform: capitalize;
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
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0;
            font-size: 14px;
            color: #374151;
            transition: border-color 0.2s;
            background: white;
            font-family: inherit;
        }
        .form-input:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }
        .form-input::placeholder {
            color: #9ca3af;
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
            padding: 10px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0;
            font-size: 14px;
            color: #374151;
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
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
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
            background: #f9fafb;
            border: 1px solid #e5e7eb;
            border-radius: 6px;
            padding: 16px;
        }
        .section-block h4 {
            font-family: 'Ubuntu', sans-serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 21.6px;
            color: rgb(52, 58, 64);
            text-transform: capitalize;
            margin: 0 0 12px 0;
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
            background-color: #e5e7eb;
            margin: 24px 0;
        }
        .button-group {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 24px;
        }
        .btn {
            width: 120px;
            display: flex;
            justify-content: center;
            align-items: center;
            gap: 8px;
            padding: 14px 28px;
            border: none;
            cursor: pointer;
            font-weight: 500;
            font-size: 12px;
            transition: all 0.2s;
            text-decoration: none;
            font-family: inherit;
        }
        .btn-reset {
            font-family: 'Ubuntu', sans-serif;
            font-size: 14px;
            font-weight: 400;
            line-height: 14px;
            text-align: center;
            text-transform: none;
            vertical-align: middle;
            white-space: pre;
            
            background-color: rgb(195, 189, 189);
            color: rgb(255, 255, 255);
            
            height: 44.4727px;
            width: 120.859px;
            border: 1.25px solid rgb(195, 189, 189);
            margin: 0px;
            padding: 14px 40px;
            
            position: static;
            float: right;
            display: block;
            cursor: pointer;
            
            overflow: clip;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out;
            box-sizing: border-box;
            border-radius: 3px;
        }
        .btn-reset:hover {
            background-color: rgb(175, 169, 169);
            border-color: rgb(175, 169, 169);
        }

        .btn-reset:active { 
            color: rgb(0, 0, 0);
            background-color: rgb(148, 148, 148);
            border-color: rgb(126, 126, 126);
            box-shadow: 0 0 0 4px rgba(148, 148, 148, 0.7), 0 0 8px 2px rgba(148, 148, 148, 0.4), 0 0 16px 4px rgba(126,126,126,0.25);
        }
        .btn-submit {
            background: #25577A;
            color: white;
        }
        .btn-submit:hover {
            background: #374151;
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
            border: 1px solid #d1d5db;
            border-radius: 0;
            padding: 8px 12px;
            min-width: 200px;
        }
        .search-input {
            border: none;
            outline: none;
            background: transparent;
            font-size: 14px;
            color: #374151;
            width: 100%;
        }
        .search-input::placeholder {
            color: #9ca3af;
        }
        .toolbar-actions {
            display: flex;
            gap: 0;
        }
        .toolbar-actions > * {
            margin-left: 0;
        }

       
        .btn-icon {
            font-family: 'Ubuntu', sans-serif;
            font-size: 14px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 14px;
            text-decoration: none;
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: middle;
            white-space: pre;
            word-spacing: 0px;
            
            background-attachment: scroll;
            background-color: rgb(195, 189, 189);
            background-image: none;
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            
            height: 44.4727px;
            width: 120.859px;
            border: 1.25px solid rgb(195, 189, 189);
            border-top: 1.25px solid rgb(195, 189, 189);
            border-right: 1.25px solid rgb(195, 189, 189);
            border-bottom: 1.25px solid rgb(195, 189, 189);
            border-left: 1.25px solid rgb(195, 189, 189);
            margin: 0px;
            padding: 14px 40px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: block;
            clear: none;
            z-index: auto;
            
            overflow: clip;
            cursor: pointer;
            visibility: visible;
            
            transform: none;
            transition: color 0.15s ease-in-out, background-color 0.15s ease-in-out, border-color 0.15s ease-in-out, box-shadow 0.15s ease-in-out, -webkit-box-shadow 0.15s ease-in-out;
            outline: none;
            outline-offset: 0px;
            box-sizing: border-box;
            resize: none;
            text-shadow: none;
            text-overflow: clip;
            word-wrap: break-word;
            box-shadow: none;
            border-top-left-radius: 3px;
            border-top-right-radius: 3px;
            border-bottom-left-radius: 3px;
            border-bottom-right-radius: 3px;
        }
        .btn-icon:hover {
            background-color: rgb(175, 169, 169);
            border-color: rgb(175, 169, 169);
        }

        /* Button Icon Styling */
        .btn-icon .fa {
            font-family: FontAwesome;
            font-size: 16px;
            font-style: normal;
            font-variant: normal;
            font-weight: 400;
            letter-spacing: normal;
            line-height: 16px;
            text-decoration: none;
            text-align: center;
            text-indent: 0px;
            text-transform: none;
            vertical-align: middle;
            white-space: normal;
            word-spacing: 0px;
            
            background-attachment: scroll;
            background-color: rgba(0, 0, 0, 0);
            background-image: none;
            background-position: 0% 0%;
            background-repeat: repeat;
            color: rgb(255, 255, 255);
            opacity: 1;
            
            border: 0px none;
            margin: 0px;
            padding: 0px;
            max-height: none;
            min-height: 0px;
            max-width: none;
            min-width: 0px;
            
            position: static;
            top: auto;
            bottom: auto;
            right: auto;
            left: auto;
            float: none;
            display: inline-block;
            clear: none;
            z-index: auto;
            
            overflow: visible;
            cursor: pointer;
            visibility: visible;
            
            transform: none;
            transition: all 0.15s ease-in-out;
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


        /* Export Dropdown Styles */
        .export-dropdown {
            position: relative;
            display: inline-block;
        }

        .export-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 1000;
            min-width: 120px;
            margin-top: 4px;
        }

        .export-menu.show {
            display: block;
        }

        .export-option {
            padding: 12px 16px;
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            border-bottom: 1px solid #f3f4f6;
            transition: background-color 0.2s;
        }

        .export-option:last-child {
            border-bottom: none;
        }

        .export-option:hover {
            background-color: #f9fafb;
        }

        .export-option:first-child {
            background-color: #f3f4f6;
        }

        /* Filter Section Styles */
        .filter-section {
            display: flex;
            align-items: center;
            gap: 12px;
        }

        .filter-dropdown {
            padding: 8px 12px;
            border: 1px solid #d1d5db;
            border-radius: 0;
            background: white;
            font-size: 14px;
            color: #374151;
            cursor: pointer;
            min-width: 80px;
        }

        .filter-dropdown:focus {
            outline: none;
            border-color: #3b82f6;
            box-shadow: 0 0 0 3px rgba(59, 130, 246, 0.1);
        }

        /* View Dropdown Styles */
        .view-dropdown {
            position: relative;
            display: inline-block;
        }

        .view-btn {
            display: flex;
            align-items: center;
            gap: 4px;
        }

        .dropdown-arrow {
            margin-left: 4px;
            opacity: 1;
        }
        
        .dropdown-arrow path {
            stroke: rgb(255, 255, 255);
            stroke-width: 1.5;
        }

        .view-menu {
            display: none;
            position: absolute;
            top: 100%;
            right: 0;
            background: white;
            border: 1px solid #d1d5db;
            border-radius: 6px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            z-index: 1000;
            min-width: 200px;
            max-height: 300px;
            overflow-y: auto;
            margin-top: 4px;
        }

        .view-menu.show {
            display: block;
        }

        .column-filter-item {
            display: flex;
            align-items: center;
            padding: 8px 16px;
            cursor: pointer;
            transition: background-color 0.2s;
            border-bottom: 1px solid #f3f4f6;
        }

        .column-filter-item:last-child {
            border-bottom: none;
        }

        .column-filter-item:hover {
            background-color: #f9fafb;
        }

        .column-checkbox {
            margin-right: 8px;
            width: 16px;
            height: 16px;
            cursor: pointer;
        }

        .column-filter-item label {
            cursor: pointer;
            font-size: 14px;
            color: #374151;
            margin: 0;
            flex: 1;
        }

        /* Updated Toolbar Layout */
        .toolbar {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-top: 16px;
        }

        .nav-tabs {
            display: flex;
            align-items: center;
            gap: 8px;
        }

        .nav-tab {
            color: #3b82f6;
            text-decoration: none;
            font-size: 14px;
            font-weight: 500;
        }

        .nav-tab.active {
            text-decoration: underline;
        }

        .nav-separator {
            color: #d1d5db;
            font-size: 14px;
        }
        .data-table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 0;
        }
        .data-table th,
        .data-table td {
            padding: 12px 16px;
            text-align: center;
            border-bottom: 1px solid #e5e7eb;
            font-size: 14px;
        }
        .data-table th {
            background: white;
            font-weight: 600;
            color: #374151;
            border-bottom: 2px solid #e5e7eb;
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
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: 500;
        }
        .status-badge.yes {
            background: #10b981;
            color: white;
        }
        .status-badge.no {
            background: #ef4444;
            color: white;
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
            background: #f9fafb;
            border-top: 1px solid #e5e7eb;
            font-size: 14px;
            color: #6b7280;
        }
        .empty-state {
            text-align: center;
            padding: 48px 24px;
            color: #6b7280;
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
            border-radius: 8px;
            box-shadow: 0 25px 50px -12px rgba(0, 0, 0, 0.25);
            max-width: 700px;
            width: 95%;
            max-height: 90vh;
            overflow: hidden;
            margin: 5% auto;
        }
        .modal-header {
            padding: 24px 24px 0 24px;
            border-bottom: 1px solid #e5e7eb;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }
        .modal-title {
            font-family: 'Ubuntu', sans-serif;
            font-size: 18px;
            font-weight: 500;
            line-height: 21.6px;
            color: rgb(52, 58, 64);
            text-transform: capitalize;
            margin: 0 0 24px 0;
        }
        .close-btn {
            color: #9ca3af;
            font-size: 24px;
            font-weight: bold;
            cursor: pointer;
            background: none;
            border: none;
            padding: 4px;
        }
        .close-btn:hover {
            color: #6b7280;
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

        /* Delete Modal Specific Styles */
        .delete-modal-content {
            max-width: 400px;
            width: 90%;
            text-align: center;
        }
        
        .delete-modal-body {
            padding: 40px 30px 30px 30px;
        }
        
        .warning-icon {
            margin-bottom: 24px;
        }
        
        .warning-circle {
            width: 80px;
            height: 80px;
            border-radius: 50%;
            background-color: #fef3c7;
            border: 2px solid #f59e0b;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto;
        }
        
        .warning-exclamation {
            font-size: 36px;
            font-weight: bold;
            color: #f59e0b;
            line-height: 1;
        }
        
        .delete-modal-title {
            font-family: 'Ubuntu', sans-serif;
            font-size: 24px;
            font-weight: 500;
            line-height: 28.8px;
            color: rgb(52, 58, 64);
            text-transform: capitalize;
            margin: 0 0 12px 0;
        }
        
        .delete-modal-message {
            font-size: 16px;
            color: #6b7280;
            margin: 0 0 32px 0;
            line-height: 1.5;
        }
        
        .delete-modal-buttons {
            display: flex;
            gap: 12px;
            justify-content: center;
        }
        
        .btn-delete-confirm {
            background: #7c3aed;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
            display: inline-block;
        }
        
        .btn-delete-confirm:hover {
            background: #6d28d9;
            color: white;
        }
        
        .btn-delete-cancel {
            background: #ef4444;
            color: white;
            padding: 12px 24px;
            border-radius: 8px;
            text-decoration: none;
            font-weight: 600;
            font-size: 14px;
            transition: background-color 0.2s;
            border: none;
            cursor: pointer;
        }
        
        .btn-delete-cancel:hover {
            background: #dc2626;
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
                <form method="POST" action="add_lesson_plan.php" id="lessonPlanForm">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    <!-- General Information Section -->
                    <div class="form-row">
                        <!-- Column 1 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>Cluster <span class="required">*</span></label>
                                <input type="text" name="CLUSTER" class="form-input" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>Theme <span class="required">*</span></label>
                                <input type="text" name="THEME" class="form-input" placeholder="" required>
                            </div>
                        </div>
                        <!-- Column 2 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>Sub-Theme <span class="required">*</span></label>
                                <input type="text" name="SUB_THEME" class="form-input" placeholder="" required>
                            </div>
                            <div class="form-group">
                                <label>Topic <span class="required">*</span></label>
                                <input type="text" name="TOPIC" class="form-input" placeholder="" required>
                            </div>
                        </div>
                        <!-- Column 3 -->
                        <div class="form-column">
                            <div class="form-group">
                                <label>Year <span class="required">*</span></label>
                                <select name="YEAR" class="form-input" required>
                                    <option value="">Select Year</option>
                                    <option value="ONE">One</option>
                                    <option value="TWO">Two</option>
                                    <option value="THREE">Three</option>
                                    <option value="FOUR">Four</option>
                                    <option value="FIVE">Five</option>
                                    <option value="SIX">Six</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Duration (Minutes) <span class="required">*</span></label>
                                <input type="text" name="DURATION" class="form-input" placeholder="" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <!-- Main Content Grid -->
                    <div class="section-grid">
                        <!-- Learning Standards Section -->
                        <div class="section-block">
                            <h4>Learning Standards</h4>
                            <div class="form-group">
                                <label>Islamic Education</label>
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
                            <h4>Performance Standards</h4>
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
                            <h4>Assessment</h4>
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
                            <h4>Instructional Design</h4>
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
                            <small style="color: #64748b; font-size: 12px;">*Refer to Technology Integration Matrix</small>
                        </div>
                        
                        <!-- Technology Integration Section -->
                        <div class="section-block">
                            <h4>Technology Integration</h4>
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
                            <small style="color: #64748b; font-size: 12px;">*Refer to Technology Integration Matrix</small>
                        </div>
                        
                        <!-- Approach Section -->
                        <div class="section-block">
                            <h4>Approach</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Inquiry"> Inquiry
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Mastery Learning"> Mastery Learning
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Problem-Based" checked> Problem-Based
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Contextual" checked> Contextual
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Project-Based"> Project-Based
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="APPROACH[]" value="Experience-Based"> Experience-Based
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Third Row Grid -->
                    <div class="section-grid-2">
                        <!-- Method Section -->
                        <div class="section-block">
                            <h4>Method</h4>
                            <div class="checkbox-group">
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Simulation" checked> Simulation
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Experimentation"> Experimentation
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Role Play" checked> Role Play
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Singing"> Singing
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Storytelling"> Storytelling
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Demonstration"> Demonstration
                                </label>
                                <label class="checkbox-option">
                                    <input type="checkbox" name="METHOD[]" value="Brainstorming"> Brainstorming
                                </label>
                            </div>
                            <div class="form-group" style="margin-top: 12px;">
                                <label>Other:</label>
                                <input type="text" name="METHOD_OTHER" class="form-input" placeholder="e.g., Playing">
                            </div>
                        </div>
                        
                        <!-- Parental Involvement Section -->
                        <div class="section-block">
                            <h4>Parental Involvement</h4>
                            <div class="checkbox-group-vertical">
                                <label class="checkbox-option">
                                    <input type="radio" name="PARENTAL_INVOLVEMENT" value="YES"> Yes
                                </label>
                                <label class="checkbox-option">
                                    <input type="radio" name="PARENTAL_INVOLVEMENT" value="NO" checked> No
                                </label>
                            </div>
                        </div>
                    </div>
                    
                    <div class="divider"></div>
                    
                    <!-- Performance Objectives and Activities -->
                    <div class="section-grid-2">
                        <!-- Performance Objectives Section -->
                        <div class="section-block">
                            <h4>Performance Objectives</h4>
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
                            <h4>Activities</h4>
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
                        <h4>Teaching Aids</h4>
                        <div class="form-group">
                            <label>Teaching Materials:</label>
                            <textarea name="TEACHING_AIDS" class="text-area" placeholder="e.g., Colored sticky notes, manila cards, adhesive tape, whiteboard, simulation materials">Colored sticky notes, manila cards, adhesive tape, whiteboard, simulation materials</textarea>
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
                <h1 class="card-title">List Subject</h1>
                <div class="toolbar">
                    <div class="filter-section">
                        <select class="filter-dropdown">
                            <option value="all">All</option>
                            <option value="active">Active</option>
                            <option value="inactive">Inactive</option>
                        </select>
                        <div class="search-bar">
                            <input type="text" class="search-input" placeholder="Search">
                        </div>
                    </div>
                    <div class="toolbar-actions">
                        <button class="btn-icon" title="Refresh">
                            <i class="fa fa-sync"></i>
                        </button>
                        <div class="view-dropdown">
                            <button class="btn-icon view-btn" title="View Options" id="viewBtn">
                                <i class="fa fa-th-list"></i>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" class="dropdown-arrow">
                                    <path d="M3 4.5L6 7.5L9 4.5" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="view-menu" id="viewMenu">
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-id" class="column-checkbox" checked>
                                    <label for="col-id">ID</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-cluster" class="column-checkbox" checked>
                                    <label for="col-cluster">Cluster</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-theme" class="column-checkbox" checked>
                                    <label for="col-theme">Theme</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-sub-theme" class="column-checkbox" checked>
                                    <label for="col-sub-theme">Sub-Theme</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-topic" class="column-checkbox" checked>
                                    <label for="col-topic">Topic</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-year" class="column-checkbox" checked>
                                    <label for="col-year">Year</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-duration" class="column-checkbox" checked>
                                    <label for="col-duration">Duration</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-instructional-design" class="column-checkbox" checked>
                                    <label for="col-instructional-design">Instructional Design</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-technology-integration" class="column-checkbox" checked>
                                    <label for="col-technology-integration">Technology Integration</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-approach" class="column-checkbox" checked>
                                    <label for="col-approach">Approach</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-method" class="column-checkbox" checked>
                                    <label for="col-method">Method</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-parental-involvement" class="column-checkbox" checked>
                                    <label for="col-parental-involvement">Parental Involvement</label>
                                </div>
                                <div class="column-filter-item">
                                    <input type="checkbox" id="col-actions" class="column-checkbox" checked>
                                    <label for="col-actions">Actions</label>
                                </div>
                            </div>
                        </div>
                        <div class="export-dropdown">
                            <button class="btn-icon export-btn" title="Export" id="exportBtn">
                                <i class="fa fa-download"></i>
                                <svg width="12" height="12" viewBox="0 0 12 12" fill="none" class="dropdown-arrow">
                                    <path d="M3 4.5L6 7.5L9 4.5" stroke="#64748b" stroke-width="1.33" stroke-linecap="round" stroke-linejoin="round"/>
                                </svg>
                            </button>
                            <div class="export-menu" id="exportMenu">
                                <div class="export-option" data-format="json">JSON</div>
                                <div class="export-option" data-format="xml">XML</div>
                                <div class="export-option" data-format="csv">CSV</div>
                                <div class="export-option" data-format="txt">TXT</div>
                                <div class="export-option" data-format="sql">SQL</div>
                                <div class="export-option" data-format="excel">MS-Excel</div>
                            </div>
                        </div>
                    </div>
                    <div class="nav-tabs">
                        <a href="#" class="nav-tab active">All</a>
                        <span class="nav-separator">|</span>
                        <a href="#" class="nav-tab">Trashed</a>
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
                                    <th data-column="LessonPlan_ID">ID</th>
                                    <th data-column="CLUSTER">Cluster</th>
                                    <th data-column="THEME">Theme</th>
                                    <th data-column="SUB_THEME">Sub-Theme</th>
                                    <th data-column="TOPIC">Topic</th>
                                    <th data-column="YEAR">Year</th>
                                    <th data-column="DURATION (minutes)">Duration</th>
                                    <th data-column="INSTRUCTIONAL DESIGN">Instructional Design</th>
                                    <th data-column="TECHNOLOGY INTEGRATION">Technology Integration</th>
                                    <th data-column="APPROACH">Approach</th>
                                    <th data-column="METHOD">Method</th>
                                    <th data-column="PARENTAL INVOLVEMENT">Parental Involvement</th>
                                    <th data-column="actions">Actions</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php $index = 1; foreach ($lessonPlans as $plan): ?>
                                <tr>
                                    <td><?php echo $plan['LessonPlan_ID']; ?></td>
                                    <td><?php echo htmlspecialchars($plan['CLUSTER']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['THEME']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['SUB_THEME']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['TOPIC']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['YEAR']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['DURATION (minutes)']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['INSTRUCTIONAL DESIGN']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['TECHNOLOGY INTEGRATION']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['APPROACH']); ?></td>
                                    <td><?php echo htmlspecialchars($plan['METHOD']); ?></td>
                                    <td>
                                        <span class="status-badge <?php echo ($plan['PARENTAL INVOLVEMENT'] === 'YES' ? 'yes' : 'no'); ?>">
                                            <?php echo ($plan['PARENTAL INVOLVEMENT'] === 'YES' ? 'Yes' : 'No'); ?>
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
        <div class="modal-content delete-modal-content">
            <div class="delete-modal-body">
                <!-- Warning Icon -->
                <div class="warning-icon">
                    <div class="warning-circle">
                        <span class="warning-exclamation">!</span>
                    </div>
                </div>
                
                <!-- Text Content -->
                <h3 class="delete-modal-title">Are you sure?</h3>
                <p class="delete-modal-message">You won't be able to revert this!</p>
                
                <!-- Action Buttons -->
                <div class="delete-modal-buttons">
                    <a href="#" id="confirmDelete" class="btn-delete-confirm">Yes, delete it</a>
                    <button class="btn-delete-cancel" id="cancelDelete">Cancel</button>
                </div>
            </div>
        </div>
    </div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const viewModal = document.getElementById('viewModal');
    const editModal = document.getElementById('editModal');
    const deleteModal = document.getElementById('deleteModal');
    const modals = [viewModal, editModal, deleteModal];

    // Prevent form resubmission
    const lessonPlanForm = document.getElementById('lessonPlanForm');
    if (lessonPlanForm) {
        lessonPlanForm.addEventListener('submit', function(e) {
            const submitBtn = this.querySelector('button[type="submit"]');
            if (submitBtn) {
                submitBtn.disabled = true;
                submitBtn.textContent = 'Submitting...';
                // Re-enable after 5 seconds as fallback
                setTimeout(() => {
                    submitBtn.disabled = false;
                    submitBtn.textContent = 'Submit';
                }, 5000);
            }
        });
    }

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
        const closeBtn = modal.querySelector('.close-btn');
        if (closeBtn) {
            closeBtn.addEventListener('click', () => closeModal(modal));
        }
    });
    window.addEventListener('click', (event) => {
        modals.forEach(modal => {
            if (event.target == modal) {
                closeModal(modal);
            }
        });
    });
    document.getElementById('cancelDelete').addEventListener('click', () => closeModal(deleteModal));

    // Export dropdown functionality
    const exportBtn = document.getElementById('exportBtn');
    const exportMenu = document.getElementById('exportMenu');
    
    if (exportBtn && exportMenu) {
        exportBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            exportMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!exportBtn.contains(e.target) && !exportMenu.contains(e.target)) {
                exportMenu.classList.remove('show');
            }
        });

        // Handle export option clicks
        exportMenu.addEventListener('click', (e) => {
            if (e.target.classList.contains('export-option')) {
                const format = e.target.getAttribute('data-format');
                exportData(format);
                exportMenu.classList.remove('show');
            }
        });
    }

    // View dropdown functionality
    const viewBtn = document.getElementById('viewBtn');
    const viewMenu = document.getElementById('viewMenu');
    
    if (viewBtn && viewMenu) {
        viewBtn.addEventListener('click', (e) => {
            e.stopPropagation();
            viewMenu.classList.toggle('show');
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', (e) => {
            if (!viewBtn.contains(e.target) && !viewMenu.contains(e.target)) {
                viewMenu.classList.remove('show');
            }
        });

        // Handle column filter checkbox clicks
        viewMenu.addEventListener('change', (e) => {
            if (e.target.classList.contains('column-checkbox')) {
                const columnId = e.target.id;
                const isVisible = e.target.checked;
                toggleColumnVisibility(columnId, isVisible);
            }
        });

        // Prevent dropdown from closing when clicking on checkboxes
        viewMenu.addEventListener('click', (e) => {
            if (e.target.type === 'checkbox' || e.target.tagName === 'LABEL') {
                e.stopPropagation();
            }
        });
    }

    // Toggle column visibility function
    function toggleColumnVisibility(columnId, isVisible) {
        // Map checkbox IDs to table column classes/attributes
        const columnMapping = {
            'col-id': 'LessonPlan_ID',
            'col-cluster': 'CLUSTER',
            'col-theme': 'THEME',
            'col-sub-theme': 'SUB_THEME',
            'col-topic': 'TOPIC',
            'col-year': 'YEAR',
            'col-duration': 'DURATION (minutes)',
            'col-instructional-design': 'INSTRUCTIONAL DESIGN',
            'col-technology-integration': 'TECHNOLOGY INTEGRATION',
            'col-approach': 'APPROACH',
            'col-method': 'METHOD',
            'col-parental-involvement': 'PARENTAL INVOLVEMENT',
            'col-actions': 'actions'
        };

        const columnName = columnMapping[columnId];
        if (columnName) {
            // Find table headers and cells to toggle visibility
            const table = document.querySelector('.data-table');
            if (table) {
                // Toggle header visibility
                const headers = table.querySelectorAll('th');
                headers.forEach(header => {
                    if (header.textContent.trim() === columnName || 
                        header.getAttribute('data-column') === columnName) {
                        header.style.display = isVisible ? '' : 'none';
                    }
                });

                // Toggle data cell visibility
                const rows = table.querySelectorAll('tbody tr');
                rows.forEach(row => {
                    const cells = row.querySelectorAll('td');
                    cells.forEach((cell, index) => {
                        const header = headers[index];
                        if (header && (header.textContent.trim() === columnName || 
                            header.getAttribute('data-column') === columnName)) {
                            cell.style.display = isVisible ? '' : 'none';
                        }
                    });
                });
            }
        }
    }

    // Export data function
    function exportData(format) {
        const lessonPlans = <?php echo json_encode($lessonPlans); ?>;
        
        switch(format) {
            case 'json':
                exportJSON(lessonPlans);
                break;
            case 'xml':
                exportXML(lessonPlans);
                break;
            case 'csv':
                exportCSV(lessonPlans);
                break;
            case 'txt':
                exportTXT(lessonPlans);
                break;
            case 'sql':
                exportSQL(lessonPlans);
                break;
            case 'excel':
                exportExcel(lessonPlans);
                break;
        }
    }

    function exportJSON(data) {
        const jsonString = JSON.stringify(data, null, 2);
        downloadFile(jsonString, 'lesson_plans.json', 'application/json');
    }

    function exportXML(data) {
        let xmlString = '<' + '?xml version="1.0" encoding="UTF-8"?>' + '\\n<lesson_plans>\\n';
        data.forEach(plan => {
            xmlString += '  <lesson_plan>\\n';
            Object.keys(plan).forEach(key => {
                xmlString += `    <${key}>${plan[key] || ''}</${key}>\\n`;
            });
            xmlString += '  </lesson_plan>\\n';
        });
        xmlString += '</lesson_plans>';
        downloadFile(xmlString, 'lesson_plans.xml', 'application/xml');
    }

    function exportCSV(data) {
        if (data.length === 0) return;
        
        const headers = Object.keys(data[0]);
        const csvContent = [
            headers.join(','),
            ...data.map(row => headers.map(header => `"${(row[header] || '').toString().replace(/"/g, '""')}"`).join(','))
        ].join('\n');
        
        downloadFile(csvContent, 'lesson_plans.csv', 'text/csv');
    }

    function exportTXT(data) {
        let txtContent = 'LESSON PLANS EXPORT\n';
        txtContent += '==================\n\n';
        
        data.forEach((plan, index) => {
            txtContent += `Lesson Plan ${index + 1}:\n`;
            txtContent += '-------------------\n';
            Object.keys(plan).forEach(key => {
                txtContent += `${key.replace(/_/g, ' ').toUpperCase()}: ${plan[key] || 'N/A'}\n`;
            });
            txtContent += '\n';
        });
        
        downloadFile(txtContent, 'lesson_plans.txt', 'text/plain');
    }

    function exportSQL(data) {
        let sqlContent = '-- Lesson Plans Export\n';
        sqlContent += '-- Generated on: ' + new Date().toISOString() + '\n\n';
        
        data.forEach(plan => {
            const columns = Object.keys(plan);
            const values = columns.map(col => `'${(plan[col] || '').toString().replace(/'/g, "''")}'`);
            sqlContent += `INSERT INTO lesson_plan (${columns.join(', ')}) VALUES (${values.join(', ')});\n`;
        });
        
        downloadFile(sqlContent, 'lesson_plans.sql', 'application/sql');
    }

    function exportExcel(data) {
        // For Excel export, we'll create a CSV that Excel can open
        exportCSV(data);
    }

    function downloadFile(content, filename, mimeType) {
        const blob = new Blob([content], { type: mimeType });
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = filename;
        document.body.appendChild(a);
        a.click();
        document.body.removeChild(a);
        window.URL.revokeObjectURL(url);
    }

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
                <form action="update_plan.php" method="POST" id="editForm">
                    <input type="hidden" name="LessonPlan_ID" value="${planData.LessonPlan_ID}">
                    <input type="hidden" name="csrf_token" value="<?php echo $_SESSION['csrf_token']; ?>">
                    
                    <div class="form-row">
                        <div class="form-column">
                            <div class="form-group">
                                <label>CLUSTER <span class="required">*</span></label>
                                <input type="text" name="CLUSTER" class="form-input" value="${planData.CLUSTER || ''}" required>
                            </div>
                            <div class="form-group">
                                <label>THEME <span class="required">*</span></label>
                                <input type="text" name="THEME" class="form-input" value="${planData.THEME || ''}" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label>Sub-Theme <span class="required">*</span></label>
                                <input type="text" name="SUB_THEME" class="form-input" value="${planData.SUB_THEME || ''}" required>
                            </div>
                            <div class="form-group">
                                <label>Topic <span class="required">*</span></label>
                                <input type="text" name="TOPIC" class="form-input" value="${planData.TOPIC || ''}" required>
                            </div>
                        </div>
                        <div class="form-column">
                            <div class="form-group">
                                <label>Year <span class="required">*</span></label>
                                <select name="YEAR" class="form-input" required>
                                    <option value="">Select Year</option>
                                    <option value="ONE" ${planData.YEAR === 'ONE' ? 'selected' : ''}>One</option>
                                    <option value="TWO" ${planData.YEAR === 'TWO' ? 'selected' : ''}>Two</option>
                                    <option value="THREE" ${planData.YEAR === 'THREE' ? 'selected' : ''}>Three</option>
                                    <option value="FOUR" ${planData.YEAR === 'FOUR' ? 'selected' : ''}>Four</option>
                                    <option value="FIVE" ${planData.YEAR === 'FIVE' ? 'selected' : ''}>Five</option>
                                    <option value="SIX" ${planData.YEAR === 'SIX' ? 'selected' : ''}>Six</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label>Duration (Minutes) <span class="required">*</span></label>
                                <input type="text" name="DURATION" class="form-input" value="${planData['DURATION (minutes)'] || ''}" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="button-group">
                        <button type="button" class="btn btn-reset" onclick="closeModal(editModal)">Cancel</button>
                        <button type="submit" class="btn btn-submit">Save Changes</button>
                    </div>
                </form>
            `;
            openModal(editModal);
            
            // Add form submission handler for edit form
            const editForm = document.getElementById('editForm');
            if (editForm) {
                editForm.addEventListener('submit', function(e) {
                    const submitBtn = this.querySelector('button[type="submit"]');
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Saving...';
                        // Re-enable after 5 seconds as fallback
                        setTimeout(() => {
                            submitBtn.disabled = false;
                            submitBtn.textContent = 'Save Changes';
                        }, 5000);
                    }
                });
            }
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

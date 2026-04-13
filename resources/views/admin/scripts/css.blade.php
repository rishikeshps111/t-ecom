<link href="https://cdn.datatables.net/1.13.6/css/dataTables.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.datatables.net/rowgroup/1.4.1/css/rowGroup.bootstrap5.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css" rel="stylesheet">
<link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
<link rel="stylesheet" href="https://code.jquery.com/ui/1.13.2/themes/base/jquery-ui.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/2.4.2/css/buttons.dataTables.min.css">

<style>
    .select2-container {
        width: 100% !important;
    }

    .priority-select {
        padding: 6px 10px;
        font-weight: 600;
    }

    .priority-select.low {
        background-color: #d4edda;
        color: #155724;
    }

    .priority-select.medium {
        background-color: #fff3cd;
        color: #856404;
    }

    .priority-select.high {
        background-color: #f8d7da;
        color: #721c24;
    }

    .chat-box {
        background: #fff;
        border-radius: 12px;
        padding: 20px;
        height: 400px;
        overflow-y: auto;
        border: 1px solid #eee;
    }

    .chat-message {
        margin-bottom: 15px;
        display: flex;
    }

    .chat-left {
        justify-content: flex-start;
    }

    .chat-right {
        justify-content: flex-end;
    }

    .chat-bubble {
        max-width: 65%;
        padding: 12px 15px;
        border-radius: 14px;
        background: #f1f3f5;
        font-size: 14px;
    }

    .chat-right .chat-bubble {
        background: #0d6efd;
        color: #fff;
    }

    .chat-bubble small {
        display: block;
        font-size: 11px;
        margin-top: 6px;
        opacity: 0.7;
    }

    .chat-input {
        border-top: 1px solid #dee2e6;
        padding-top: 10px;
    }

    .chat-input .input-group {
        border-radius: 25px;
        overflow: hidden;
        box-shadow: 0 1px 4px rgba(0, 0, 0, 0.1);
    }

    .chat-input textarea.form-control {
        border: none;
        resize: none;
        padding: 10px 15px;
        height: 50px;
        border-radius: 25px 0 0 25px;
    }

    .chat-input button {
        border-radius: 0 25px 25px 0;
        min-width: 100px;
    }

    /* Scrollbar Styling */
    .chat-box::-webkit-scrollbar {
        width: 6px;
    }

    .chat-box::-webkit-scrollbar-thumb {
        background-color: rgba(0, 0, 0, 0.2);
        border-radius: 3px;
    }

    .read-tick {
        font-size: 11px;
        opacity: 0.6;
        margin-left: 5px;
    }

    .header-section-right .dropdown-menu {
        border-radius: 12px;
        background-color: #fff;
    }

    .header-section-right .dropdown-item {
        border-radius: 8px;
        transition: background 0.2s;
    }

    .header-section-right .dropdown-item:hover {
        background-color: #f1f1f1;
    }

    .header-section-right .badge {
        font-size: 0.65rem;
        padding: 3px 6px;
    }

    .header-section-right a.btn {
        background: transparent;
        border: none;
        position: relative;
    }

    .no-arrow::after {
        display: none !important;
    }

    .action-dropdown .dropdown-item {
        font-size: 13px;
    }

    input[readonly] {
        background-color: #8a8a8a !important;
        color: #565b5f !important;
        cursor: not-allowed;
    }

    .select2-container--default.select2-container--disabled .select2-selection--single {
        background-color: #8a8a8a;
        cursor: default;
    }

    #table th,
    #table td {
        border: 1px solid #e3e6f0;
    }
</style>
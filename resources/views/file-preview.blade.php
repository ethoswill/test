<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>File Preview - {{ $fileManager->original_name }}</title>
    <style>
        body {
            font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #f8fafc;
        }
        .container {
            max-width: 1200px;
            margin: 0 auto;
            background: white;
            border-radius: 8px;
            box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
            overflow: hidden;
        }
        .header {
            background: #1f2937;
            color: white;
            padding: 20px;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .file-info {
            padding: 20px;
            border-bottom: 1px solid #e5e7eb;
        }
        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 15px;
        }
        .info-item {
            display: flex;
            flex-direction: column;
        }
        .info-label {
            font-weight: 600;
            color: #374151;
            margin-bottom: 5px;
        }
        .info-value {
            color: #6b7280;
        }
        .preview-section {
            padding: 20px;
            text-align: center;
        }
        .image-preview {
            max-width: 100%;
            max-height: 600px;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
        }
        .file-icon {
            font-size: 64px;
            margin-bottom: 20px;
        }
        .back-button {
            display: inline-block;
            background: #3b82f6;
            color: white;
            padding: 10px 20px;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
        }
        .back-button:hover {
            background: #2563eb;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="header">
            <h1>{{ $fileManager->original_name }}</h1>
        </div>
        
        <div class="file-info">
            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">File Type</div>
                    <div class="info-value">
                        @if(str_starts_with($fileManager->mime_type, 'image/'))
                            🖼️ Image
                        @elseif(str_starts_with($fileManager->mime_type, 'application/pdf'))
                            📄 PDF
                        @elseif(str_starts_with($fileManager->mime_type, 'text/'))
                            📝 Text
                        @else
                            📁 File
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">File Size</div>
                    <div class="info-value">{{ $fileManager->file_size_formatted ?? 'Unknown' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Assigned Store</div>
                    <div class="info-value">{{ $fileManager->store->name ?? 'Unassigned' }}</div>
                </div>
                <div class="info-item">
                    <div class="info-label">Uploaded</div>
                    <div class="info-value">{{ $fileManager->created_at->format('M j, Y g:i A') }}</div>
                </div>
            </div>
            @if($fileManager->description)
                <div class="info-item" style="margin-top: 15px;">
                    <div class="info-label">Description</div>
                    <div class="info-value">{{ $fileManager->description }}</div>
                </div>
            @endif
        </div>
        
        <div class="preview-section">
            @if($fileManager->isImage())
                <img src="{{ $fileManager->file_url }}" alt="{{ $fileManager->original_name }}" class="image-preview">
            @else
                <div class="file-icon">
                    @if(str_starts_with($fileManager->mime_type, 'application/pdf'))
                        📄
                    @elseif(str_starts_with($fileManager->mime_type, 'text/'))
                        📝
                    @else
                        📁
                    @endif
                </div>
                <p>{{ $fileManager->original_name }}</p>
                <p>This file type cannot be previewed in the browser.</p>
            @endif
            
            <a href="{{ url('/admin/file-managers') }}" class="back-button">← Back to File Manager</a>
        </div>
    </div>
</body>
</html>

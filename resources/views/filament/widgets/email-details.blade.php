<div class="space-y-6">
    <!-- Email Header -->
    <div class="bg-gray-50 p-4 rounded-lg">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div>
                <h4 class="font-semibold text-gray-900">From</h4>
                <p class="text-sm text-gray-600">{{ $email->from_name }} <{{ $email->from_email }}></p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">To</h4>
                <p class="text-sm text-gray-600">{{ $email->to_name }} <{{ $email->to_email }}></p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">Sent</h4>
                <p class="text-sm text-gray-600">{{ $email->formatted_sent_at }}</p>
            </div>
            <div>
                <h4 class="font-semibold text-gray-900">Status</h4>
                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium
                    @if($email->status === 'sent') bg-green-100 text-green-800
                    @elseif($email->status === 'failed') bg-red-100 text-red-800
                    @else bg-yellow-100 text-yellow-800 @endif">
                    {{ $email->status_badge }}
                </span>
            </div>
        </div>
    </div>

    <!-- Subject -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Subject</h4>
        <p class="text-sm text-gray-600 bg-white p-3 rounded border">{{ $email->subject }}</p>
    </div>

    <!-- Template Info -->
    @if($email->template_name)
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Template Used</h4>
        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
            {{ $email->template_name }}
        </span>
    </div>
    @endif

    <!-- Attachments -->
    @if($email->hasAttachments())
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Attachments</h4>
        <div class="space-y-2">
            @foreach($email->attachments as $attachment)
            <div class="flex items-center space-x-2 text-sm text-gray-600">
                <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.172 7l-6.586 6.586a2 2 0 102.828 2.828l6.414-6.586a4 4 0 00-5.656-5.656l-6.415 6.585a6 6 0 108.486 8.486L20.5 13"></path>
                </svg>
                <span>{{ $attachment }}</span>
            </div>
            @endforeach
        </div>
    </div>
    @endif

    <!-- Error Message -->
    @if($email->status === 'failed' && $email->error_message)
    <div>
        <h4 class="font-semibold text-red-900 mb-2">Error Message</h4>
        <p class="text-sm text-red-600 bg-red-50 p-3 rounded border">{{ $email->error_message }}</p>
    </div>
    @endif

    <!-- Email Body -->
    <div>
        <h4 class="font-semibold text-gray-900 mb-2">Message</h4>
        <div class="bg-white p-4 rounded border max-h-96 overflow-y-auto">
            <pre class="whitespace-pre-wrap text-sm text-gray-600 font-sans">{{ $email->body }}</pre>
        </div>
    </div>
</div>

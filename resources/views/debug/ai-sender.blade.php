@extends('layouts.app')

@section('title', 'Debug AI Sender')

@section('content')
<div class="max-w-3xl mx-auto space-y-6">
  <div class="bg-white rounded-lg shadow border p-6">
    <h1 class="text-xl font-semibold mb-4">Test Suggestion Valeur (AI)</h1>
    <form id="aiDebugForm" class="space-y-4">
      @csrf
      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Pickup city</label>
          <input class="w-full border rounded px-3 py-2" id="pickup_city" value="fes"/>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Delivery city</label>
          <input class="w-full border rounded px-3 py-2" id="delivery_city" value="tanger"/>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Preferred date</label>
          <input type="date" class="w-full border rounded px-3 py-2" id="preferred_date" value="{{ now()->addDays(7)->toDateString() }}"/>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Weight (kg)</label>
          <input type="number" step="0.1" class="w-full border rounded px-3 py-2" id="package_weight" value="0.2"/>
        </div>
      </div>

      <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
        <div>
          <label class="block text-sm text-gray-700 mb-1">Item type</label>
          <input class="w-full border rounded px-3 py-2" id="item_type" value="Documents"/>
        </div>
        <div>
          <label class="block text-sm text-gray-700 mb-1">Dimensions</label>
          <input class="w-full border rounded px-3 py-2" id="dimensions" value="20x20"/>
        </div>
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Description</label>
        <textarea class="w-full border rounded px-3 py-2" id="description" rows="3">un colis contient HP Victus 16 pc gaming i7 RTX 3060 16GB 512GB occasion bon état</textarea>
      </div>

      <div>
        <label class="block text-sm text-gray-700 mb-1">Additional info</label>
        <textarea class="w-full border rounded px-3 py-2" id="additional_info" rows="2"></textarea>
      </div>

      <div class="flex items-center gap-3">
        <button type="button" id="runTest" class="px-4 py-2 rounded bg-indigo-600 text-white hover:bg-indigo-700">Appeler AI</button>
        <div id="status" class="text-sm text-gray-600"></div>
      </div>
    </form>
  </div>

  <div class="bg-white rounded-lg shadow border p-6">
    <h2 class="font-semibold mb-2">Résultat</h2>
    <pre id="result" class="bg-gray-50 p-3 rounded text-sm"></pre>
  </div>
</div>

@push('scripts')
<script>
document.getElementById('runTest').addEventListener('click', async () => {
  const payload = {
    pickup_city: document.getElementById('pickup_city').value,
    delivery_city: document.getElementById('delivery_city').value,
    preferred_date: document.getElementById('preferred_date').value,
    package_weight: document.getElementById('package_weight').value,
    item_type: document.getElementById('item_type').value,
    dimensions: document.getElementById('dimensions').value,
    description: document.getElementById('description').value,
    additional_info: document.getElementById('additional_info').value,
  };
  const status = document.getElementById('status');
  const out = document.getElementById('result');
  status.textContent = 'Appel en cours…';
  out.textContent = '';
  try {
    const res = await fetch('{{ route('ai.suggest-declared-value') }}', {
      method: 'POST',
      headers: { 'Content-Type': 'application/json', 'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content },
      body: JSON.stringify(payload)
    });
    const data = await res.json();
    status.textContent = res.ok ? 'Succès' : ('Erreur HTTP ' + res.status);
    out.textContent = JSON.stringify(data, null, 2);
  } catch (e) {
    status.textContent = 'Exception: ' + e.message;
  }
});
</script>
@endpush
@endsection



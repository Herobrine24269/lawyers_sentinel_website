@extends('layouts.admin')

@section('content')
<div class="space-y-6">

    <div class="bg-white rounded-xl border border-slate-200 p-6 shadow-sm flex items-center justify-between">
        <div>
            <h1 class="text-xl font-extrabold text-slate-900">Manage Legal Specialization Services</h1>
            <p class="text-xs text-slate-500 mt-1">Add or toggle legal service categories (Criminal, Divorce, Affidavit, Civil, Corporate)</p>
        </div>
        <span class="text-xs bg-emerald-100 text-emerald-800 font-bold px-3 py-1.5 rounded-lg border border-emerald-200">
            Active Categories: {{ $services->where('is_active', true)->count() }}
        </span>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
        
        <!-- Add New Service Form -->
        <div class="lg:col-span-1">
            <div class="bg-white rounded-xl border border-slate-200 p-5 shadow-sm">
                <h3 class="font-extrabold text-sm text-slate-900 mb-4 pb-2 border-b border-slate-150">Add Legal Service Category</h3>

                <form action="{{ route('admin.services.store') }}" method="POST" class="space-y-4">
                    @csrf
                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Service Name</label>
                        <input type="text" name="name" required placeholder="e.g. Criminal Law"
                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600">
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Description</label>
                        <textarea name="description" rows="3" placeholder="Brief summary of legal coverage..."
                            class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg focus:ring-2 focus:ring-blue-600"></textarea>
                    </div>

                    <div>
                        <label class="block text-xs font-semibold text-slate-700 mb-1">Icon Identifier</label>
                        <select name="icon" class="w-full px-3 py-2 text-xs border border-slate-300 rounded-lg bg-white">
                            <option value="gavel">Gavel / General</option>
                            <option value="shield-halved">Shield / Criminal Defense</option>
                            <option value="people-roof">Family / Divorce</option>
                            <option value="file-contract">Document / Affidavit</option>
                            <option value="scale-balanced">Scales / Civil Law</option>
                        </select>
                    </div>

                    <button type="submit" class="w-full bg-blue-600 hover:bg-slate-900 text-white font-bold py-2.5 rounded-lg text-xs transition">
                        Add Service Category
                    </button>
                </form>
            </div>
        </div>

        <!-- Existing Services Table -->
        <div class="lg:col-span-2">
            <div class="bg-white rounded-xl border border-slate-200 shadow-sm overflow-hidden">
                <div class="p-4 border-b border-slate-150 font-bold text-sm text-slate-900">
                    Current Legal Practice Categories
                </div>
                <div class="overflow-x-auto">
                    <table class="w-full text-left text-xs text-slate-700">
                        <thead class="bg-slate-50 text-slate-500 font-bold uppercase tracking-wider border-b border-slate-200">
                            <tr>
                                <th class="px-6 py-3.5">Category Name</th>
                                <th class="px-6 py-3.5">Slug</th>
                                <th class="px-6 py-3.5">Description</th>
                                <th class="px-6 py-3.5">Status</th>
                                <th class="px-6 py-3.5 text-right">Action</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-slate-150 font-medium">
                            @foreach($services as $svc)
                            <tr class="hover:bg-slate-50 transition">
                                <td class="px-6 py-4 font-bold text-slate-900 flex items-center gap-2">
                                    <i class="fa-solid fa-{{ $svc->icon }} text-blue-600"></i> {{ $svc->name }}
                                </td>
                                <td class="px-6 py-4 font-mono text-slate-500 text-[11px]">{{ $svc->slug }}</td>
                                <td class="px-6 py-4 max-w-xs truncate text-slate-600">{{ $svc->description }}</td>
                                <td class="px-6 py-4">
                                    @if($svc->is_active)
                                        <span class="bg-emerald-100 text-emerald-800 text-[10px] font-bold px-2 py-0.5 rounded border border-emerald-300">Active</span>
                                    @else
                                        <span class="bg-slate-100 text-slate-500 text-[10px] font-bold px-2 py-0.5 rounded border border-slate-300">Disabled</span>
                                    @endif
                                </td>
                                <td class="px-6 py-4 text-right">
                                    <form action="{{ route('admin.services.toggle', $svc->id) }}" method="POST">
                                        @csrf
                                        <button type="submit" class="text-xs font-bold text-blue-600 hover:underline">
                                            Toggle Status
                                        </button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

    </div>

</div>
@endsection

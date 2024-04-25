<x-app-layout>
    <x-slot name="header" class="">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Update Product') }}
            </h2>
            <div>
                <a href="{{ route('product.index') }}">
                    <x-primary-button class="ml-20">
                        Go Back to Products
                    </x-primary-button>
                </a>
            </div>
        </div>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">

            <div class="max-w-md mx-auto p-6 bg-white rounded-lg shadow-xl">
                @if ($errors->any())
                    <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-2 rounded mb-4">
                        <ul class="list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('product.update', ['id' => $product->id]) }}" method="post" class="space-y-4">
                    @csrf
                    @method('put')

                    <div class="flex flex-col">
                        <label for="name" class="text-sm font-semibold text-gray-600">Name</label>
                        <input type="text" name="name" value="{{ $product->name ?? '' }}" id="name"
                            placeholder="Enter Name" class="mt-1 p-2 border rounded-md">
                    </div>

                    <div class="flex flex-col">
                        <label for="quantity" class="text-sm font-semibold text-gray-600">Quantity</label>
                        <input type="number" name="quantity" value="{{ $product->quantity ?? '' }}" id="quantity"
                            placeholder="Enter Quantity" class="mt-1 p-2 border rounded-md">
                    </div>

                    <div class="flex flex-col">
                        <label for="price" class="text-sm font-semibold text-gray-600">Price</label>
                        <input type="number" name="price" value="{{ $product->price ?? '' }}" id="price"
                            step="any" placeholder="Enter Price" class="mt-1 p-2 border rounded-md">
                    </div>

                    <div class="flex flex-col">
                        <label for="description" class="text-sm font-semibold text-gray-600">Description</label>
                        <textarea name="description" id="description" cols="30" rows="5" placeholder="Enter Description"
                            class="mt-1 p-2 border rounded-md">{{ $product->description ?? '' }}</textarea>
                    </div>

                    <div class="flex justify-end">
                        <input type="reset" value="Reset" class="px-4 py-2 bg-gray-300 rounded-md mr-2">
                        <input type="submit" value="Update" class="px-4 py-2 bg-blue-600 text-white rounded-md">
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>

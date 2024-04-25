<x-app-layout>
    {{-- JSPDF CDN --}}
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf/2.4.0/jspdf.umd.min.js"></script>
    <script src="https://cdnjs.cloudflare.com/ajax/libs/jspdf-autotable/3.5.16/jspdf.plugin.autotable.min.js"></script>

    {{-- DataTable CDN --}}
    <link rel="stylesheet" type="text/css" href="https://cdn.datatables.net/1.11.4/css/dataTables.bootstrap4.min.css">
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/jquery.dataTables.min.js"></script>
    <script src="https://cdn.datatables.net/1.11.4/js/dataTables.bootstrap4.min.js"></script>

    <x-slot name="header" class="">
        <div class="flex flex-row justify-between">
            <h2 class="font-semibold text-xl text-gray-800 leading-tight">
                {{ __('Products') }}
            </h2>
            <div class="mb-4">
                <a href="{{ route('product.create') }}" class="px-4 py-2 bg-blue-600 text-white rounded-md">Create a New
                    Product</a>
            </div>
        </div>
    </x-slot>

    <div class="max-w-4xl mx-auto p-6">
        @if (session()->has('success'))
            <div class="relative bg-green-100 border border-green-400 text-green-700 px-4 py-2 rounded mb-4">
                <span class="absolute top-0 right-0 px-2 py-1 cursor-pointer"
                    onclick="this.parentElement.style.display='none';">
                    &times;
                </span>
                {{ session('success') }}
            </div>
        @endif

        <div class="overflow-x-auto">
            <table id="productsTable" class="min-w-full bg-white">
                <thead>
                    <tr>
                        <th class="px-4 py-2 border">Name</th>
                        <th class="px-4 py-2 border">Quantity</th>
                        <th class="px-4 py-2 border">Price</th>
                        <th class="px-4 py-2 border">Description</th>
                        <th class="px-4 py-2 border">Action</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($products as $product)
                        <tr>
                            <td class="px-4 py-2 border">{{ $product->name ?? '' }}</td>
                            <td class="px-4 py-2 border">{{ $product->quantity ?? '' }}</td>
                            <td class="px-4 py-2 border">{{ $product->price ?? '' }}</td>
                            <td class="px-4 py-2 border">{{ $product->description ?? '' }}</td>
                            <td class="px-4 py-2 border">
                                <div class="flex space-x-2">
                                    <a href="{{ route('product.edit', ['id' => $product->id]) }}"
                                        class="px-4 py-2 bg-blue-600 text-white rounded-md">Update</a>
                                    <form action="{{ route('product.destroy', ['id' => $product->id]) }}"
                                        method="post">
                                        @csrf
                                        @method('delete')
                                        <input type="submit" value="Delete"
                                            class="px-4 py-2 bg-red-600 text-white rounded-md">
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
            @if ($products->count() > 0)
                <x-primary-button id="downloadPdf" data-products="{{ json_encode($products) }}"
                    class="float-right mt-2">
                    Download as PDF
                </x-primary-button>
            @endif
        </div>
    </div>
</x-app-layout>

<style>
    /* DataTables Pagination Custom Styles */
    .dataTables_wrapper .dataTables_paginate .paginate_button {
        padding: 0.5rem 0.75rem;
        margin-left: 0.25rem;
        display: inline-block;
        color: #4a5568;
        border: 1px solid transparent;
        border-radius: 0.25rem;
        transition: background-color 0.2s ease-in-out, border-color 0.2s ease-in-out,
            color 0.2s ease-in-out;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button:hover {
        background-color: #e2e8f0;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.active {
        background-color: #4299e1;
        color: #ffffff;
        border-color: transparent;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.disabled {
        opacity: 0.5;
        cursor: not-allowed;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:focus {
        outline: none;
    }

    .dataTables_wrapper .dataTables_paginate .paginate_button.current:hover {
        background-color: #4299e1;
        color: #ffffff;
        border-color: transparent;
    }
</style>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        const productsElement = document.querySelector('[data-products]');

        if (productsElement) {
            productsElement.addEventListener('click', function() {
                // Initialize jsPDF
                window.jsPDF = window.jspdf.jsPDF;
                const doc = new window.jsPDF({
                    orientation: 'portrait',
                    unit: 'mm',
                    format: 'a4'
                });
                var pageWidth = doc.internal.pageSize.getWidth();
                var productsData = this.getAttribute('data-products');
                var products = JSON.parse(productsData);

                // Add a title to the PDF
                doc.text('Products Report', pageWidth / 2, 10, {
                    align: 'center'
                });

                // Define table columns and rows
                var columns = ["Name", "Quantity", "Price", "Description"];
                var data = products.map(product => [
                    product.name,
                    product.quantity,
                    product.price,
                    product.description
                ]);

                doc.autoTable({
                    theme: 'plain',
                    head: [columns],
                    body: data,
                    startY: 15, // mm
                    margin: {
                        left: 9,
                        right: 9
                    },
                    styles: {
                        fontSize: 10, // points
                        lineWidth: 0.254, // mm
                        lineColor: 'black'
                    },
                    bodyStyles: {
                        halign: 'left',
                        valign: 'middle',
                        cellPadding: {
                            top: 1,
                            right: 2,
                            bottom: 1,
                            left: 2
                        }
                    },
                    headStyles: {
                        halign: 'center',
                        valign: 'middle',
                        fillColor: '#c0bcbc'
                    },
                    foot: [],
                    showFoot: 'lastPage',
                    footStyles: {
                        fontStyle: 'bold',
                        fillColor: '#cbcbcb'
                    }
                });

                // Save the PDF
                doc.save('Products Report.pdf');
            });
        }
    });

    $(document).ready(function() {
        $('#productsTable').DataTable({
            "pagingType": "full_numbers",
            "lengthMenu": [
                [5, 10, 25, 50, -1],
                [5, 10, 25, 50, "All"]
            ],
            "order": [],
            "language": {
                "search": "_INPUT_",
                "searchPlaceholder": "Search records",
                "lengthMenu": "Show _MENU_ entries",
                "info": "Showing _START_ to _END_ of _TOTAL_ entries",
                "infoEmpty": "Showing 0 to 0 of 0 entries",
                "zeroRecords": "No matching records found",
                "infoFiltered": "(filtered from _MAX_ total entries)",
                "paginate": {
                    "first": "First",
                    "last": "Last",
                    "next": "Next",
                    "previous": "Previous"
                },
            }
        });
    });
</script>

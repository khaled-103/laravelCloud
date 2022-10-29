<x-layout>
    <div class="holder py-5 ">
        <h2 class="h2 text-center">All Keys Stored</h2>

        <div style="width:40%;margin:auto;  border-radius:4px;" class="">
            <ul class="mt-3 row py-3">
                @foreach($images as $key => $image)
                <div class="col-lg-6 col-12">
                    <li style="border-radius:4px;" class="text-white py-1 px-2 bg-dark mb-2">{{$image->key}}</li>
                </div>
                @endforeach
            </ul>
        </div>
    </div>
</x-layout>

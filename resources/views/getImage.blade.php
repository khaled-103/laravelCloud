<x-layout>
    <div class="back">
        <div class="form-holder">
            <form method="post" action="/getImage" id="form-get-image">
                @csrf
                <h2 class="text-center">Get Image</h2>
                <input type="text" name="key" id="key" class="form-control mb-3" placeholder="Key..." />
                <div class="text-center">
                    <button type="submit" class="btn btn-dark">Get Image</button>
                </div>
                <div class="text-center">
                    <img src="" class="mt-5" id="display-image" style="max-width: 100%; margin:auto;">
                </div>
                <div id="notFound" class="alert alert-danger text-center mt-4">Image Not Found</div>
                <div id="imageSource"></div>
                <div id="cacheContent"></div>
            </form>

        </div>
    </div>
</x-layout>

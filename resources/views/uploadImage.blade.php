<x-layout>
    @if(session()->has('success'))
    <div class="alert alert-success text-center" style="margin-bottom: 0 !important;">
        {{session()->get('success')}}
    </div>
    @endif
    <div class="back">
        <div class="form-holder">
            <form method="post" action="{{route('storeImage')}}" enctype="multipart/form-data">
                @csrf
                <h2 class="text-center">Upload Image</h2>
                <input type="text" name="key" class="form-control @error('key') is-invalid @enderror" placeholder="Key..." />
                @error('key')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
                <input type="file" id="image" name="image" accept="image/*" class="form-control mt-3 @error('image') is-invalid @enderror" />
                @error('image')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
                <div class="text-center mt-5">
                    <button type="submit" class="btn btn-dark">
                        Upload
                    </button>
                </div>
            </form>
        </div>
    </div>
</x-layout>

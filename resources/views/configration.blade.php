<x-layout>
    <div class="back">
        <div id="alert-clear-cache" class="alert alert-success text-center"></div>
        @if(session()->has('success'))
        <div id="alert-session" class="alert alert-success text-center" style="margin-bottom: 0 !important;">
            {{session()->get('success')}}
        </div>
        @endif
        <div class="form-holder">
            <form method="POST" action="{{route('storeConfig')}}">
                @csrf
                <h2 class="text-center">Configrarion</h2>
                <select class="form-select @error('key') is-invalid @enderror" name="replacment_policy">
                    <!-- <option hidden>Select Replacement Policies</option> -->
                    @foreach($policies as $policy)
                    <option value="{{$policy->id}}" @if($currentConfigData->replacment_policy == $policy->id) selected @endif>{{$policy->name}}</option>
                    @endforeach
                    <!-- <option value="lru">Least Recently Used</option> -->
                </select>
                @error('key')
                <p class="invalid-feedback">{{ $message }}</p>
                @enderror
                <label for="" class="my-3">Cache Capacity: </label>
                <input type="Number" name="capacity" min="1" max="100" class="form-control mb-3" value="{{$currentConfigData->capacity}}" />

                <div class="row justify-content-center">
                    <div class="text-center col-auto mx-2">
                        <button type="submit" class="btn btn-success text-center">Submit</button>
                        <input type="button" id="clear_cache" value="Clear Cache" class="btn btn-danger text-center" />
                    </div>
                </div>
            </form>
        </div>
    </div>
</x-layout>

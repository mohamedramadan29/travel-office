<div>
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label for=""> حدد الدولة </label>
                <select name="country_id" id="" class="form-control" required wire:model.live="country_id">
                    <option value=""> -- حدد الدولة -- </option>
                    @foreach ($countries as $country)
                        <option value="{{ $country->id }}" @selected(old('country_id') == $country->id)>
                            {{ $country->name }}
                        </option>
                    @endforeach
                </select>
                <strong class="text-danger" id="country_id_error"></strong>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for=""> المحافظات </label>
                <select name="governrate_id" id="" class="form-control" required wire:model.live="governorate_id">
                    <option value=""> -- حدد المحافظة -- </option>
                    @foreach ($governorates as $governorate)
                        <option value="{{ $governorate->id }}" @selected(old('governrate_id') == $governorate->id)>
                            {{ $governorate->name }}
                        </option>
                    @endforeach
                </select>
                <strong class="text-danger" id="governorate_id_error"></strong>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="form-group">
                <label for=""> المدن </label>
                <select name="city_id" id="" class="form-control" required wire:model.live="city_id">
                    <option value=""> -- حدد المدينة -- </option>
                    @foreach ($cities as $city)
                        <option value="{{ $city->id }}" @selected(old('city_id') == $city->id)>
                            {{ $city->name }}
                        </option>
                    @endforeach
                </select>
                <strong class="text-danger" id="city_id_error"></strong>
            </div>
        </div>
    </div>
</div>

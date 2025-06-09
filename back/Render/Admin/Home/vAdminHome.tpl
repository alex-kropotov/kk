
<div class="container mx-auto px-4 py-4 py-md-5">


    <!-- Filters Section -->
    <div class="card mb-4">
        <div class="card-body">
            <div class="row g-3">
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Property Type</label>
                    <select name="property_type" class="form-select">
                        <option value="" selected>All Types</option>
                        <option value="house">House</option>
                        <option value="apartment">Apartment</option>
                        <option value="office">Office</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">Deal Type</label>
                    <select name="deal_type" class="form-select">
                        <option value="" selected>All Deals</option>
                        <option value="sale">Sale</option>
                        <option value="rent">Rent</option>
                    </select>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <label class="form-label">City</label>
                    <input type="text" name="city" placeholder="Enter city" class="form-control">
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Min Price</label>
                            <input type="number" name="price_min" placeholder="Min" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Price</label>
                            <input type="number" name="price_max" placeholder="Max" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Min Rooms</label>
                            <input type="number" name="rooms_min" placeholder="Min" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Rooms</label>
                            <input type="number" name="rooms_max" placeholder="Max" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="col-12 col-md-6 col-lg-3">
                    <div class="row g-2">
                        <div class="col-6">
                            <label class="form-label">Min Area</label>
                            <input type="number" name="area_min" placeholder="Min m²" class="form-control">
                        </div>
                        <div class="col-6">
                            <label class="form-label">Max Area</label>
                            <input type="number" name="area_max" placeholder="Max m²" class="form-control">
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Properties Grid -->
    <div class="row g-4">
        <!-- Property 1 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card property-card h-100">
                <div class="card-img-top" style="height: 200px;">
                    <div class="no-image-placeholder">
                        <span>No image</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-success bg-opacity-10 text-success me-2">Аренда</span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Дом</span>
                        </div>
                    </div>
                    <h5 class="card-title mb-3">Белград, Земун</h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Жилая площадь:</span>
                            <span class="fw-medium">120m²</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Комнаты:</span>
                            <span class="fw-medium">4</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Этаж:</span>
                            <span class="fw-medium">0 из 0</span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="h4 text-primary fw-bold">€800/month</div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property 2 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card property-card h-100">
                <img src="https://via.placeholder.com/400x300?text=Belgrade" class="card-img-top" style="height: 200px; object-fit: cover;" alt="Белград">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary me-2">Продажа</span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Квартира</span>
                        </div>
                    </div>
                    <h5 class="card-title mb-3">Белград, Новый Белград</h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Жилая площадь:</span>
                            <span class="fw-medium">75.5m²</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Комнаты:</span>
                            <span class="fw-medium">3</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Этаж:</span>
                            <span class="fw-medium">0 из 0</span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="h4 text-primary fw-bold">€150&nbsp;000</div>
                    </div>
                    <div class="mt-3 d-flex gap-2">
                        <small class="text-muted"><i class="bi bi-image"></i></small>
                        <small class="text-muted"><i class="bi bi-image"></i></small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Property 3 -->
        <div class="col-12 col-md-6 col-lg-4">
            <div class="card property-card h-100">
                <div class="card-img-top" style="height: 200px;">
                    <div class="no-image-placeholder">
                        <span>No image</span>
                    </div>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary me-2">Продажа</span>
                            <span class="badge bg-secondary bg-opacity-10 text-secondary">Квартира</span>
                        </div>
                    </div>
                    <h5 class="card-title mb-3">, </h5>
                    <div class="mb-4">
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Жилая площадь:</span>
                            <span class="fw-medium">0m²</span>
                        </div>
                        <div class="d-flex justify-content-between small mb-2">
                            <span>Комнаты:</span>
                            <span class="fw-medium">0</span>
                        </div>
                        <div class="d-flex justify-content-between small">
                            <span>Этаж:</span>
                            <span class="fw-medium">0 из 0</span>
                        </div>
                    </div>
                    <div class="border-top pt-3">
                        <div class="h4 text-primary fw-bold">€0</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="d-flex justify-content-between align-items-center mb-4">
                <h1 class="h3 mb-0">Izmeni nekretninu</h1>
                <!--div class="btn-group" role="group">
                    <button type="button" class="btn btn-primary">Serbian</button>
                    <button type="button" class="btn btn-outline-secondary">English</button>
                    <button type="button" class="btn btn-outline-secondary">Russian</button>
                </div-->
            </div>



            <form>
                <!-- Basic Information Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-4">Osnovne informacije</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Vrsta nekretnine</label>
                                <select class="form-select" name="property-type">
                                    <option value="house">Kuća</option>
                                    <option value="apartment">Stan</option>
                                    <option value="office">Kancelarija</option>
                                </select>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Vrsta transakcije</label>
                                <select class="form-select">
                                    <option value="sale">Prodaja</option>
                                    <option value="rent">Izdavanje</option>
                                </select>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Localized Information Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-4">Lokacija</h2>
                        <div class="row g-3">
                            <div class="col-md-6">
                                <label class="form-label">Mesto</label>
                                <input type="text" id="citySearch" class="form-control">
                                <div class="alert alert-info" id="cityInfo" style="display: none;"></div>
                            </div>



                            <div class="col-md-6">
                                <label class="form-label">Deo mesta</label>
                                <input type="text" id="districtSearch" class="form-control">
                            </div>
                            <div class="col-6">
                                <label class="form-label">Ulica</label>
                                <input type="text" id="streetSearch" class="form-control">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Broj</label>
                                <input type="text" id="brojSearch" class="form-control">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Slovo</label>
                                <input type="text" id="slovoSearch" class="form-control">
                            </div>
                            <div class="col-2">
                                <label class="form-label">Ulaz</label>
                                <input type="text" id="ulazSearch" class="form-control">
                            </div>
                            <div class="col-3">
                                <label class="form-label">Sprat</label>
                                <input type="text" id="spratSearch" class="form-control">
                            </div>
                            <div class="col-3">
                                <label class="form-label">Broj sprata</label>
                                <input type="text" id="brojspratSearch" class="form-control">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Property Parameters Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-4">Property Parameters</h2>
                        <div class="row g-3">

                            <div class="col-md-3">
                                <label class="form-label">Površina (m²)</label>
                                <input type="number" class="form-control">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Sobnost</label>
                                <input type="text" class="form-control" value="4">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Spavaće</label>
                                <input type="number" class="form-control" value="3">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Kupatilo</label>
                                <input type="number" class="form-control" value="1">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Stanje</label>
                                <input type="text" class="form-control" value="Renovirano">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Nameštaj</label>
                                <input type="text" class="form-control" value="Prazno">
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Additional Features Section -->
                <!--div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-4">Additional Features</h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">Registered</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">Water Supply</label>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="form-check">
                                    <input class="form-check-input" type="checkbox">
                                    <label class="form-check-label">Sewage</label>
                                </div>
                            </div>
                        </div>
                    </div>
                </div-->

                <!-- Owner Information Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h2 class="h5 card-title mb-4">Vlasnik 1</h2>
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label">Prezime</label>
                                <input type="text" class="form-control" value="Đukać">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ime</label>
                                <input type="text" class="form-control" value="Buka">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Ime roditelja</label>
                                <input type="text" class="form-control" value="">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Važi od</label>
                                <input type="text" class="form-control" value="01.01.2025">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Email</label>
                                <input type="text" class="form-control" value="buka@dzuka.com">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Telefon</label>
                                <input type="text" class="form-control" value="+381 61 673 3445">
                            </div>


                        </div>
                    </div>
                </div>

                <!-- Files Section -->
                <div class="card shadow-sm mb-4">
                    <div class="card-body">
                        <h3 class="h5 card-title mb-4">Файлы</h3>
                        <div class="mb-3">
                            <label class="form-label">Тип файла</label>
                            <select class="form-select mb-3">
                                <option value="image">Изображение</option>
                                <option value="video">Видео</option>
                                <option value="document">Документ</option>
                            </select>

                            <div class="form-check mb-3">
                                <input class="form-check-input" type="checkbox" id="isPublic" checked>
                                <label class="form-check-label" for="isPublic">Публично доступно</label>
                            </div>

                            <label class="form-label">Выберите файл</label>
                            <input class="form-control mb-3" type="file" accept="image/*">

                            <button class="btn btn-primary w-100 mb-3" disabled>Загрузить</button>

                            <div class="text-danger small mb-3">Failed to load existing files</div>

                            <h4 class="h6">Загруженные файлы</h4>
                            <div class="mt-2"></div>
                        </div>
                    </div>
                </div>

                <!-- Form Actions -->
                <div class="d-flex justify-content-end gap-3">
                    <button type="button" class="btn btn-outline-secondary">Otkaži</button>
                    <button type="submit" class="btn btn-primary">Sačuvaj</button>
                </div>
            </form>
        </div>
    </div>
</div>


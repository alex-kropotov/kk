<!-- filter form -->
<div class="modal similar__modal fade " id="filterModal">
    <div class="modal-dialog modal-dialog-centered">
        <div class="modal-content">
            <div class="max-content similar__form form__padding">
                <div class="d-flex mb-3 align-items-center justify-content-between">
                    <h6 class="mb-0">pretraga po svim filterima</h6>
                    <button class="btn-close" type="button" data-bs-dismiss="modal" aria-label="Close">
                    </button>
                </div>

                <!-- Booking Search -->

                <div class="booking-inner clearfix">
                    <form action="#" class="form1 column clearfix">


                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <label>Usluga</label>
                                <div class="select1_inner">
                                    <select class="select2 select" style="width: 100%">
                                        <option value="1">Prodaja</option>
                                        <option value="2">Izdavanje</option>
                                        <option value="3">Novogradnja</option>

                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <label>Kategorija</label>
                                <div class="select1_inner">
                                    <select class="select2 select" style="width: 100%">
                                        <option value="1">Stan</option>
                                        <option value="1">Kuća</option>
                                        <option value="2">Plac</option>
                                        <option value="3">Lokal</option>
                                        <option value="4">Poslovni prostor</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <label>Mesto</label>
                                <div class="select1_inner">
                                    <select class="select2 select" style="width: 100%">
                                        <option value="1">Novi Sad</option>
                                        <option value="1">Petrovaradin</option>
                                        <option value="1">Sremska Kamenica</option>
                                        <option value="1">Futog</option>

                                    </select>
                                </div>
                            </div>
                        </div>

                        <div class="col5 c3">
                            <div class="select1_wrapper">
                                <label>Cena</label>
                                <div class="row input">
                                    <div class="col-md-6 form-group">
                                        <input name="sizeFrom" type="text" placeholder="od" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input name="sizeTo" type="text" placeholder="do" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper">
                                <label>Površina</label>
                                <div class="row input">
                                    <div class="col-md-6 form-group">
                                        <input name="priceFrom" type="text" placeholder="od" required>
                                    </div>
                                    <div class="col-md-6 form-group">
                                        <input name="priceTo" type="text" placeholder="do" required>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <div class="select1_wrapper">
                                    <label>Lokacija</label>
                                    <div class="select1_inner">

                                        <ul class="accordion-box clearfix">
                                            <li class="accordion block">
                                                <div class="acc-btn">lokacija</div>
                                                <div class="acc-content">
                                                    <div class="content">
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="anaselje" id="anaselje">
                                                            <label for="anaselje">Adamovićevo naselje</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="adice" id="adice">
                                                            <label for="adice">Adice</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="avnaselje" id="avnaselje">
                                                            <label for="avnaselje">Avijatičarsko naselje</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="banatic" id="banatic">
                                                            <label for="banatic">Banatić</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="bistrica" id="bistrica">
                                                            <label for="bistrica">Bistrica</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="betanija" id="betanija">
                                                            <label for="betanija">Betanija</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="bulevar" id="bulevar">
                                                            <label for="bulevar">Bulevar</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="centar" id="centar">
                                                            <label for="centar">Centar</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <div class="select1_wrapper">
                                    <label>Struktura</label>
                                    <div class="select1_inner">

                                        <ul class="accordion-box clearfix">
                                            <li class="accordion block">
                                                <div class="acc-btn">izaberi strukturu</div>
                                                <div class="acc-content">
                                                    <div class="content">
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="garsonjera" id="garsonjera">
                                                            <label for="garsonjera">garsonjera</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="jednosoban" id="jednosoban">
                                                            <label for="jednosoban">jednosoban</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="dvosoban" id="dvosoban">
                                                            <label for="dvosoban">dvosoban</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="trosoban" id="trosoban">
                                                            <label for="trosoban">trosoban</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="cetvorosoban"
                                                                   id="cetvorosoban">
                                                            <label for="cetvorosoban">četvorosoban</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="petosoban" id="petosobang">
                                                            <label for="petosoban">petosoban i veći</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="penthaus" id="penthaus">
                                                            <label for="penthaus">penthaus</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <label>broj spavaćih soba</label>
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="1" id="1">
                                                            <label for="1">1 spavaća</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="2" id="2">
                                                            <label for="2">2 spavaće</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="3" id="3">
                                                            <label for="3">3 spavaće</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="4" id="4">
                                                            <label for="4">4 spavaće i više</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <label>broj kupatila</label>
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="1k" id="1k">
                                                            <label for="1k">1 kupatilo</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="2k" id="2k">
                                                            <label for="2k">2 kupatila</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->

                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <div class="select1_wrapper">
                                    <label>Spratnost</label>
                                    <div class="select1_inner">

                                        <ul class="accordion-box clearfix">
                                            <li class="accordion block">
                                                <div class="acc-btn">sprat</div>
                                                <div class="acc-content">
                                                    <div class="content">
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="su" id="su">
                                                            <label for="su">suteren</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="pr" id="pr">
                                                            <label for="pr">prizemlje</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="1s" id="1s">
                                                            <label for="1s">1 sprat</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="2s" id="2s">
                                                            <label for="2s">2-4 sprata</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="5s" id="5s">
                                                            <label for="5s">5-10 sprata</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="11s" id="11s">
                                                            <label for="11s">11+</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="po" id="po">
                                                            <label for="po">potkrovlje</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->

                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <div class="select1_wrapper">
                                    <label>parking</label>
                                    <div class="select1_inner">

                                        <ul class="accordion-box clearfix">
                                            <li class="accordion block">
                                                <div class="acc-btn">garaža</div>
                                                <div class="acc-content">
                                                    <div class="content">
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="pp" id="pp">
                                                            <label for="pp">privatni parking</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="pz" id="pz">
                                                            <label for="pz">parking zgrade</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="sz" id="sz">
                                                            <label for="sz">slobodna zona</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->


                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c3">
                            <div class="select1_wrapper border">
                                <div class="select1_wrapper">
                                    <label>ostalo</label>
                                    <div class="select1_inner">

                                        <ul class="accordion-box clearfix">
                                            <li class="accordion block">
                                                <div class="acc-btn">nameštenost</div>
                                                <div class="acc-content">
                                                    <div class="content">
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="na" id="na">
                                                            <label for="na">namešten</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="pn" id="pn">
                                                            <label for="pn">polunamešten</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="pra" id="pra">
                                                            <label for="pra">prazan</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <label>opremljenost objekta</label>
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="lift" id="lift">
                                                            <label for="lift">lift</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="interfon" id="interfon">
                                                            <label for="interfon">interfon</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="kamere" id="kamere">
                                                            <label for="kamere">kamere</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="alarm" id="alarm">
                                                            <label for="alarm">alarm</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="obezbedjenje"
                                                                   id="obezbedjenje">
                                                            <label for="obezbedjenje">obezbedjenje</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="kolica" id="kolica">
                                                            <label for="kolica">prilaz za kolica</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <label>ostalo</label>
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="terasa" id="terasa">
                                                            <label for="terasa">terasa</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="bazen" id="bazen">
                                                            <label for="bazen">bazen</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="basta" id="basta">
                                                            <label for="basta">bašta</label>
                                                            <span>30</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="dupleks" id="dupleks">
                                                            <label for="dupleks">dupleks</label>
                                                            <span>20</span>
                                                        </div>
                                                        <!-- checkbox item end -->
                                                        <!-- checkbox item -->
                                                        <div class="query__input checkbox wow fadeInUp">
                                                            <input type="checkbox" name="salonac" id="salonac">
                                                            <label for="salonac">salonac</label>
                                                            <span>15</span>
                                                        </div>
                                                        <!-- checkbox item end -->


                                                    </div>
                                                </div>
                                            </li>

                                        </ul>

                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col5 c6">
                            <button type="submit" class="btn-form1-submit">pretraži</button>
                        </div>
                    </form>
                </div>

            </div>
        </div>
    </div>
</div>

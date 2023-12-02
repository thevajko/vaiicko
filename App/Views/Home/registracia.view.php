<script src="public/js/formCheck.js" defer></script>
<section class="h-100 bg-dark">
    <div class="container py-5 h-100">
        <div class="row d-flex justify-content-center align-items-center h-100">
            <div class="col">
                <div class="card card-registration my-4">
                    <div class="row g-0">
                        <div class="col-xl-6 d-none d-xl-flex flex-wrap align-items-center">
                            <img src="public/images/logos/mf100_Logo.png"
                                 alt="MfStovka logo" class="img-fluid"
                                 style="border-top-left-radius: .25rem; border-bottom-left-radius: .25rem;" />
                        </div>
                        <div class="col-xl-6">
                            <form id="runnerForm" class="row g-3 needs-validation">
                                <div class="card-body p-md-5 text-black">
                                    <h3 class="mb-5 text-uppercase">Registácia bežca</h3>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" id="name" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="name">Meno</label>
                                            </div>
                                        </div>
                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" id="surname" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="surname">Priezvisko</label>
                                            </div>
                                        </div>
                                    </div>

                                    <div class="d-md-flex justify-content-start align-items-center mb-4 py-2">

                                        <h6 class="mb-0 me-4">Pohlavie: </h6>

                                        <div class="form-check form-check-inline mb-0 me-4">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="femaleGender"
                                                   value="option1" required/>
                                            <label class="form-check-label" for="femaleGender">Žena</label>
                                        </div>

                                        <div class="form-check form-check-inline mb-0 me-4">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="maleGender"
                                                   value="option2" required/>
                                            <label class="form-check-label" for="maleGender">Muž</label>
                                        </div>

                                        <div class="form-check form-check-inline mb-0">
                                            <input class="form-check-input" type="radio" name="inlineRadioOptions" id="otherGender"
                                                   value="option3" required/>
                                            <label class="form-check-label" for="otherGender">Iné</label>
                                        </div>

                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="date" id="birthDate" class="form-control form-control-lg" required/>
                                        <label class="form-label" for="birthDate">Dátum narodenia</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="text" id="street" class="form-control form-control-lg" required/>
                                        <label class="form-label" for="street">Ulica</label>
                                    </div>

                                    <div class="row">
                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" id="city" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="city">Mesto</label>
                                            </div>
                                        </div>

                                        <div class="col-md-6 mb-4">
                                            <div class="form-outline">
                                                <input type="text" pattern="\d{3}[ ]?\d{2}" id="postalCode" class="form-control form-control-lg" required/>
                                                <label class="form-label" for="postalCode">PSČ</label>
                                            </div>
                                        </div>
                                    </div>


                                    <div class="form-outline mb-4">
                                        <input type="email" id="email" class="form-control form-control-lg" required/>
                                        <label class="form-label" for="email">Email</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="password" class="form-control form-control-lg" required/>
                                        <label class="form-label" for="password">Heslo</label>
                                    </div>

                                    <div class="form-outline mb-4">
                                        <input type="password" id="passwordRepeat" class="form-control form-control-lg" required/>
                                        <label class="form-label" for="passwordRepeat">Zopakujte heslo</label>
                                    </div>
                                    <span id="error" class="alert alert-danger" hidden></span>
                                    <div class="d-flex justify-content-end pt-3">
                                        <button id="submitButton" type="submit" class="btn btn-warning btn-lg ms-2">Registrovať</button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</section>
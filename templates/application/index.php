<?php load_templates('layouts/top') ?>
    <div class="content">
        <div class="panel-header <?=config('theme')['panel_color']?>">
            <div class="page-inner py-5">
                <div class="d-flex align-items-left align-items-md-center flex-column flex-md-row">
                    <div>
                        <h2 class="text-white pb-2 fw-bold">Detail Aplikasi</h2>
                        <h5 class="text-white op-7 mb-2">Update detail aplikasi</h5>
                    </div>
                    <div class="ml-md-auto py-2 py-md-0">
                        
                    </div>
                </div>
            </div>
        </div>
        <div class="page-inner mt--5">
            <div class="row row-card-no-pd">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <?php if($success_msg): ?>
                            <div class="alert alert-success"><?=$success_msg?></div>
                            <?php endif ?>
                            <form action="" method="post">
                                <input type="hidden" name="app[id]" value="<?=$data->id?>">
                                <div class="form-group">
                                    <label for="">Nama</label>
                                    <input type="text" name="app[name]" class="form-control" value="<?=$data->name?>" required>
                                </div>
                                <div class="form-group">
                                    <label for="">Alamat</label>
                                    <textarea name="app[address]" id="" required class="form-control mb-2" placeholder="Alamat Disini..."><?=$data->address?></textarea>
                                </div>
                                <div class="form-group">
                                    <label for="">Telepon/Handphone/Whatsapp</label>
                                    <input type="tel" name="app[phone]" class="form-control" value="<?=$data->phone?>">
                                </div>
                                <div class="form-group">
                                    <label for="">E-Mail</label>
                                    <input type="email" name="app[email]" class="form-control" value="<?=$data->email?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Jagel API Key</label>
                                    <input type="text" name="app[jagel_api_key]" class="form-control" value="<?=$data->jagel_api_key?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Bank BTN Production API Key</label>
                                    <input type="text" name="app[bank_btn_api_key]" class="form-control" value="<?=$data->bank_btn_api_key?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Bank BTN Sandbox API Key</label>
                                    <input type="text" name="app[bank_btn_sandbox_api_key]" class="form-control" value="<?=$data->bank_btn_sandbox_api_key?>">
                                </div>
                                <div class="form-group">
                                    <label for="">Cipta Kerja Production API Key</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="prod" name="app[cipta_kerja_api_key]" readonly value="<?=$data->cipta_kerja_api_key?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="generate('#prod')">Generate</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="">Cipta Kerja Sandbox API Key</label>
                                    <div class="input-group">
                                        <input type="text" class="form-control" id="sandbox" name="app[cipta_kerja_sandbox_api_key]" readonly value="<?=$data->cipta_kerja_sandbox_api_key?>">
                                        <div class="input-group-append">
                                            <button class="btn btn-outline-secondary" type="button" onclick="generate('#sandbox')">Generate</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <button class="btn btn-primary">Submit</button>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <script>
    function generate(target) {
        let length = 50;
        const characters = 'abcdefghijklmnopqrstuvwxyz';
        let result = ' ';
        const charactersLength = characters.length;
        for(let i = 0; i < length; i++) {
            result += 
            characters.charAt(Math.floor(Math.random() * charactersLength));
        }
        document.querySelector(target).value = result
    }
    </script>
<?php load_templates('layouts/bottom') ?>
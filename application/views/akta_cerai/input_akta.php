 <div class="form-group p-3">
     <div class="row">
         <label
             for="<?= $form['id'] ?>"
             class="col-sm-3 text-end  col-form-label"><?= $form['label'] ?></label>
         <div class="col-sm-9">
             <div class="input-group">
                 <input
                     <?= $form['aditional_attribute'] ?? null ?>
                     type="text"
                     name="<?= $form['name'] ?>"
                     class="form-control <?= $form['aditional_class'] ?? null ?>"
                     id="<?= $form['id'] ?>"
                     placeholder="<?= $form['placeholder'] ?>"
                     value="<?= $form['value'] ?? null ?>" />
                 <div class="input-group-text">
                     <?= $form['icon'] ?? "<i class='ti ti-pencil'></i>" ?>
                 </div>
             </div>
         </div>
     </div>
 </div>
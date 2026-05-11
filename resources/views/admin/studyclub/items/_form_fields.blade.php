<div class="row">
    <div class="col-md-6 mb-3">
        <label class="form-label">Categoria *</label>
        <input type="text" class="form-control" name="category" required 
               placeholder="Ex: ORTODONTIA">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Tipo *</label>
        <select class="form-select" name="type" required>
            <option value="article">Artigo</option>
            <option value="interview">Entrevista</option>
            <option value="special">Especial</option>
        </select>
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Rótulo do Tipo *</label>
    <input type="text" class="form-control" name="type_label" required 
           placeholder="Ex: Artigo Original, Entrevista...">
</div>

<div class="mb-3">
    <label class="form-label">Autor(es) *</label>
    <input type="text" class="form-control" name="author" required>
</div>

<div class="mb-3">
    <label class="form-label">Título *</label>
    <input type="text" class="form-control" name="title" required>
</div>

<div class="mb-3">
    <label class="form-label">Resumo *</label>
    <textarea class="form-control" name="resumo" rows="3" required></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Achados *</label>
    <textarea class="form-control" name="achados" rows="2" required></textarea>
</div>

<div class="mb-3">
    <label class="form-label">Implicações *</label>
    <textarea class="form-control" name="implicacoes" rows="2" required></textarea>
</div>

<div class="mb-3">
    <label class="form-label">URL Externa (DentalGO) *</label>
    <input type="url" class="form-control" name="external_url" required
           placeholder="https://dentalgo.com.br/...">
</div>

<div class="row">
    <div class="col-md-4 mb-3">
        <label class="form-label">Ícone (Bootstrap Icons)</label>
        <input type="text" class="form-control" name="icon" value="bi-journal-text">
    </div>
    <div class="col-md-6 mb-3">
        <label class="form-label">Likes Legados</label>
        <input type="number" class="form-control" name="likes" value="0" min="0">
    </div>
</div>

<div class="mb-3">
    <label class="form-label">Imagem {{ isset($isEdit) ? '(Deixe vazio para manter atual)' : '' }}</label>
    <input type="file" class="form-control" name="image" accept="image/*">
</div>

<html>
    <head>
        <title>GMO Label</title>
        <style>
            body {
                margin: 0;
                padding: 2em;
                font: normal normal 16px/1.4 sans-serif;
            }
            form fieldset {
                margin: 0 0 1.5em 0;
                padding: 0;
                border: none;
                outline: none;
            }
            form label {
                display: block;
                margin:  0 0 0.5em 0;
                font-size:  90%;
                opacity: 0.5;
            }
            form button[type="submit"] {
                display: inline-block;
                margin: 1em 0;
                padding: 0.5em 1.5em;
                border: none;
                outline: none;
                background: #444;
                color: white;
                border-radius: 4px;
                cursor: pointer;
            }
        </style>
    </head>
    <body>
        <h2>GMO Label</h2>
        <form enctype="multipart/form-data" method="POST">
            <fieldset>
                <label>CSV File</label>
                <input type="file" name="csvfile">
            </fieldset>

            <fieldset>
                <label>Label Template</label>
                <select name="definition">
                    <option>Please select…</option>
                    <?php foreach ($definitions as $definition): ?>
                        <option value="<?= htmlspecialchars($definition['title']) ?>"><?= htmlspecialchars($definition['title']) ?></option>
                    <?php endforeach; ?>
                </select>
            </fieldset>

            <fieldset>
                <button type="submit">Generate labels</button>
            </fieldset>
        </form>
    </body>
</html>

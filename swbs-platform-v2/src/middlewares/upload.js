const path = require('path');
const fs = require('fs');
const multer = require('multer');
const { v4: uuidv4 } = require('uuid');
const sharp = require('sharp');

const uploadsRoot = path.join(__dirname, '..', '..', 'public', 'uploads');

function ensureDir(dir) {
  if (!fs.existsSync(dir)) {
    fs.mkdirSync(dir, { recursive: true });
  }
}

function createUploader(subfolder) {
  const dest = path.join(uploadsRoot, subfolder);
  ensureDir(dest);

  const storage = multer.diskStorage({
    destination(req, file, cb) {
      cb(null, dest);
    },
    filename(req, file, cb) {
      const ext = path.extname(file.originalname).toLowerCase();
      const safeName = `${uuidv4()}${ext}`;
      cb(null, safeName);
    },
  });

  const fileFilter = (req, file, cb) =&gt; {
    const allowed = ['image/jpeg', 'image/png', 'image/webp'];
    if (!allowed.includes(file.mimetype)) {
      return cb(new Error('Invalid file type. Only JPG, PNG, WEBP allowed.'));
    }
    cb(null, true);
  };

  const upload = multer({
    storage,
    limits: { fileSize: 5 * 1024 * 1024 },
    fileFilter,
  });

  return {
    uploadSingle(fieldName) {
      return async (req, res, next) =&gt; {
        upload.single(fieldName)(req, res, async (err) =&gt; {
          if (err) {
            return res.status(400).json({ error: err.message });
          }
          if (!req.file) {
            return next();
          }

          const inputPath = req.file.path;
          const ext = path.extname(inputPath).toLowerCase();
          const baseName = path.basename(inputPath, ext);
          const webpPath = path.join(path.dirname(inputPath), `${baseName}.webp`);
          const thumbPath = path.join(path.dirname(inputPath), `${baseName}-thumb.webp`);

          try {
            await sharp(inputPath).toFormat('webp').toFile(webpPath);

            await sharp(inputPath).resize(400).toFormat('webp').toFile(thumbPath);

            fs.unlinkSync(inputPath);

            req.file.optimizedPath = webpPath;
            req.file.thumbPath = thumbPath;
            req.file.relativePath = path
              .relative(path.join(__dirname, '..', '..', 'public'), webpPath)
              .replace(/\\+/g, '/');
            req.file.relativeThumbPath = path
              .relative(path.join(__dirname, '..', '..', 'public'), thumbPath)
              .replace(/\\+/g, '/');
          } catch (processingError) {
            console.error(processingError);
          }

          next();
        });
      };
    },
  };
}

module.exports = {
  createUploader,
};
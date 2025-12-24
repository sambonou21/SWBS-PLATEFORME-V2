import { spawn } from "node:child_process";

const cmd = spawn("php", ["artisan", "db:seed", "--class=Database\\Seeders\\AdminUserSeeder"], {
  stdio: "inherit",
});

cmd.on("exit", (code) => {
  if (code === 0) {
    console.log("Compte administrateur SWBS vérifié/créé avec succès.");
  } else {
    console.error("Erreur lors de l'initialisation de l'administrateur (code " + code + ").");
  }
});
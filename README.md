# Appetized

Marketing site til app-udviklingsbureauet Appetized med automatisk deploy til Firebase Hosting via GitHub Actions.

## Firebase opsætning
1. Opret et Firebase-projekt og aktiver Hosting.
2. Opdater `.firebaserc` med dit projekt-id (`your-firebase-project-id`) for lokal udvikling.
3. Tilføj GitHub secret `FIREBASE_PROJECT_ID` med samme projekt-id; workflowet stopper med en fejl, hvis den ikke er sat.
4. Generér et CI token: `firebase login:ci` og tilføj det som GitHub secret `FIREBASE_TOKEN`.
5. Ved push til `main` deployer workflowet automatisk til Firebase Hosting.

## Lokal udviklings
```bash
# start en simpel server
python -m http.server 3000 --directory public
```
Besøg `http://localhost:3000` for at se siden.

export class FileSize {
      public countFileSize(size: number): string {
            const sufixes = ['B', 'kB', 'MB', 'GB', 'TB'];
            const i = Math.floor(Math.log(size) / Math.log(1024));
            return `${(size / Math.pow(1024, i)).toFixed(2)} ${sufixes[i]}`;
      }
}
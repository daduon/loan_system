import { jsPDF } from 'jspdf';
import autoTable from 'jspdf-autotable';
import * as XLSX from 'xlsx-js-style';

export const exportExcel = (exportData: any) => {
    const newList = [] as any[];
    const headerList = exportData.columns.map((data:any) => data.header);
    const keyList = exportData.columns.map((data:any) => data.dataKey);
    exportData.body.forEach((data:any) => {
        const newObject = {} as any;
        keyList.forEach((key:any) => {
            newObject[key] = data[key];
        });
        newList.push(newObject);
    });
    const workBook = XLSX.utils.book_new();
    const ws: XLSX.WorkSheet = XLSX.utils.json_to_sheet([]);
    XLSX.utils.sheet_add_aoa(ws, [headerList]);
    XLSX.utils.sheet_add_json(ws, newList, {
        origin: 'A2',
        skipHeader: true,
    });
    XLSX.utils.book_append_sheet(workBook, ws, exportData.sheetName);
    XLSX.writeFile(workBook, `${exportData.fileName || 'default-book'}.xlsx`);
};

export const exportPDF = ({ format = 'a4', title = '', setUnderLineTitle = false, headerList = [], list = [], footerText = '', fileName ='', orientation = 'p', fontSizeTable = 10 }) => {
    let doc = new jsPDF({orientation: 'p',unit : 'cm',format : format});
    if (orientation === 'l') {
        doc = new jsPDF({orientation: 'l',unit : 'cm',format : format});
    }

    const font = ""
    doc.setFont(font, 'normal')

    doc.setFontSize(16).text(title, 0.5, 1.0);

    if (setUnderLineTitle) {
        doc.setLineWidth(0.01).line(0.5, 1.1, 8.0, 1.1);
    }

    autoTable(doc, {
        columns: headerList,
        body: list,
        startY: 1.2,
        styles: {
            overflow: 'linebreak',
            fontSize: fontSizeTable,
            font: font,
            fontStyle: 'normal'
        },
    });

    if (footerText) {
        doc.setFontSize(11)
            .setTextColor(0, 0, 255)
            .text(footerText, 0.5, doc.internal.pageSize.height - 0.5);
    }

    doc.save(`${fileName}.pdf`);
};

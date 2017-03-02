
// EveryoneWorksWithEveryoneDlg.h : header file
//

#pragma once


// CEveryoneWorksWithEveryoneDlg dialog
class CEveryoneWorksWithEveryoneDlg : public CDialog
{
// Construction
public:
	CEveryoneWorksWithEveryoneDlg(CWnd* pParent = NULL);	// standard constructor

// Dialog Data
	enum { IDD = IDD_EVERYONEWORKSWITHEVERYONE_DIALOG };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);	// DDX/DDV support

	int Test(int nParticipantCount, CString & sText);

// Implementation
protected:
	HICON m_hIcon;

	// Generated message map functions
	virtual BOOL OnInitDialog();
	afx_msg void OnSysCommand(UINT nID, LPARAM lParam);
	afx_msg void OnPaint();
	afx_msg HCURSOR OnQueryDragIcon();
	DECLARE_MESSAGE_MAP()
public:
	afx_msg void OnBnClickedButton1();

private:
	CStdioFile m_file;
};

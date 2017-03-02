
// EveryoneWorksWithEveryoneDlg.cpp : implementation file
//

#include "stdafx.h"
#include "EveryoneWorksWithEveryone.h"
#include "EveryoneWorksWithEveryoneDlg.h"

#ifdef _DEBUG
#define new DEBUG_NEW
#endif


// CAboutDlg dialog used for App About

class CAboutDlg : public CDialog
{
public:
	CAboutDlg();

// Dialog Data
	enum { IDD = IDD_ABOUTBOX };

	protected:
	virtual void DoDataExchange(CDataExchange* pDX);    // DDX/DDV support

// Implementation
protected:
	DECLARE_MESSAGE_MAP()
};

CAboutDlg::CAboutDlg() : CDialog(CAboutDlg::IDD)
{
}

void CAboutDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CAboutDlg, CDialog)
END_MESSAGE_MAP()


// CEveryoneWorksWithEveryoneDlg dialog




CEveryoneWorksWithEveryoneDlg::CEveryoneWorksWithEveryoneDlg(CWnd* pParent /*=NULL*/)
	: CDialog(CEveryoneWorksWithEveryoneDlg::IDD, pParent)
{
	m_hIcon = AfxGetApp()->LoadIcon(IDR_MAINFRAME);
}

void CEveryoneWorksWithEveryoneDlg::DoDataExchange(CDataExchange* pDX)
{
	CDialog::DoDataExchange(pDX);
}

BEGIN_MESSAGE_MAP(CEveryoneWorksWithEveryoneDlg, CDialog)
	ON_WM_SYSCOMMAND()
	ON_WM_PAINT()
	ON_WM_QUERYDRAGICON()
	//}}AFX_MSG_MAP
	ON_BN_CLICKED(IDC_BUTTON1, &CEveryoneWorksWithEveryoneDlg::OnBnClickedButton1)
END_MESSAGE_MAP()


// CEveryoneWorksWithEveryoneDlg message handlers

BOOL CEveryoneWorksWithEveryoneDlg::OnInitDialog()
{
	CDialog::OnInitDialog();

	// Add "About..." menu item to system menu.

	// IDM_ABOUTBOX must be in the system command range.
	ASSERT((IDM_ABOUTBOX & 0xFFF0) == IDM_ABOUTBOX);
	ASSERT(IDM_ABOUTBOX < 0xF000);

	CMenu* pSysMenu = GetSystemMenu(FALSE);
	if (pSysMenu != NULL)
	{
		BOOL bNameValid;
		CString strAboutMenu;
		bNameValid = strAboutMenu.LoadString(IDS_ABOUTBOX);
		ASSERT(bNameValid);
		if (!strAboutMenu.IsEmpty())
		{
			pSysMenu->AppendMenu(MF_SEPARATOR);
			pSysMenu->AppendMenu(MF_STRING, IDM_ABOUTBOX, strAboutMenu);
		}
	}

	// Set the icon for this dialog.  The framework does this automatically
	//  when the application's main window is not a dialog
	SetIcon(m_hIcon, TRUE);			// Set big icon
	SetIcon(m_hIcon, FALSE);		// Set small icon

	// TODO: Add extra initialization here

	return TRUE;  // return TRUE  unless you set the focus to a control
}

void CEveryoneWorksWithEveryoneDlg::OnSysCommand(UINT nID, LPARAM lParam)
{
	if ((nID & 0xFFF0) == IDM_ABOUTBOX)
	{
		CAboutDlg dlgAbout;
		dlgAbout.DoModal();
	}
	else
	{
		CDialog::OnSysCommand(nID, lParam);
	}
}

// If you add a minimize button to your dialog, you will need the code below
//  to draw the icon.  For MFC applications using the document/view model,
//  this is automatically done for you by the framework.

void CEveryoneWorksWithEveryoneDlg::OnPaint()
{
	if (IsIconic())
	{
		CPaintDC dc(this); // device context for painting

		SendMessage(WM_ICONERASEBKGND, reinterpret_cast<WPARAM>(dc.GetSafeHdc()), 0);

		// Center icon in client rectangle
		int cxIcon = GetSystemMetrics(SM_CXICON);
		int cyIcon = GetSystemMetrics(SM_CYICON);
		CRect rect;
		GetClientRect(&rect);
		int x = (rect.Width() - cxIcon + 1) / 2;
		int y = (rect.Height() - cyIcon + 1) / 2;

		// Draw the icon
		dc.DrawIcon(x, y, m_hIcon);
	}
	else
	{
		CDialog::OnPaint();
	}
}

// The system calls this function to obtain the cursor to display while the user drags
//  the minimized window.
HCURSOR CEveryoneWorksWithEveryoneDlg::OnQueryDragIcon()
{
	return static_cast<HCURSOR>(m_hIcon);
}


void CEveryoneWorksWithEveryoneDlg::OnBnClickedButton1()
{
	srand(::GetTickCount());
	CWaitCursor wait;

	m_file.Open(_T("solutions.php"), CFile::modeWrite | CFile::modeCreate | CFile::shareDenyWrite);
	m_file.WriteString(_T("<?php\n"));
	m_file.WriteString(_T("$solutions = array();\n\n"));

	for (int nParticipants = 5; nParticipants <= 10; ++nParticipants)
	{
		CString sLine;
		sLine.Format(_T("$solutions[%d] = array();\n"), nParticipants);
		m_file.WriteString(sLine);

		int nMinResult = 999;
		CString sMinText;

		for (int nTest = 0; nTest < 2000; ++nTest)
		{
			CString sText;
			int nResult = Test(nParticipants, sText);
			if (nResult < nMinResult)
			{
				nMinResult = nResult;
				sMinText = sText;
			}
		}

		m_file.WriteString(sMinText);
		m_file.WriteString(_T("\n"));
		m_file.Flush();
	}

	m_file.WriteString(_T("?>\n\n"));
	m_file.Close();
}

int CEveryoneWorksWithEveryoneDlg::Test(int nParticipantCount, CString & sOut)
{
	std::vector<std::pair<int,int> > vecAll;

	for (int n = 1; n <= nParticipantCount; ++n)
	{
		for (int m = n + 1; m <= nParticipantCount; ++m)
		{
			vecAll.push_back(std::make_pair(n,m));
		}
	}

	class CSession
	{
	public:
		bool Add(std::pair<int,int> myPair)
		{
			if (m_setTakenParticipants.find(myPair.first) != m_setTakenParticipants.end())
				return false;

			if (m_setTakenParticipants.find(myPair.second) != m_setTakenParticipants.end())
				return false;

			m_setTakenParticipants.insert(myPair.first);
			m_setTakenParticipants.insert(myPair.second);
			m_vecPairs.push_back(myPair);
			return true;
		}

		CString GetAsText()
		{
			CString sOut;
			for (UINT n = 0; n < m_vecPairs.size(); ++n)
			{
				if (n)
					sOut += _T(",");

				CString sPair;
				sPair.Format(_T("(%d-%d)"), m_vecPairs[n].first, m_vecPairs[n].second);

				sOut += sPair;
			}

			return sOut;
		}

		std::set<int> m_setTakenParticipants;
		std::vector<std::pair<int,int> > m_vecPairs;
	};

	std::vector<CSession> vecSessions;

	// Pair randomly
	while (vecAll.size())
	{
		int nPairIndex = rand() % vecAll.size();
		std::pair<int,int> myPair = vecAll[nPairIndex];

		UINT nSession = 0;
		for(;;)
		{
			if (nSession >= vecSessions.size())
				vecSessions.push_back(CSession());

			CSession & sessionCurrent = vecSessions[nSession];

			bool brc = sessionCurrent.Add(myPair);

			if (brc)
				break;

			++nSession;
		}

		// erase
		for (UINT n = nPairIndex; n + 1 < vecAll.size(); ++n)
		{
			vecAll[n] = vecAll[n + 1];
		}
		vecAll.pop_back();
	}

	// output result
	for (UINT nSession = 0; nSession < vecSessions.size(); ++nSession)
	{
		CString sLine;
		sLine.Format(_T("array_push($solutions[%d],'%s');\n"),
					 nParticipantCount,
					 vecSessions[nSession].GetAsText());
		sOut += sLine;
	}

	return vecSessions.size();
}

